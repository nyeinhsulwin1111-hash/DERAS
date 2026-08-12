<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }
    public function messages(): array
    {
        {
        return [
            'email.required' => 'အီးမေးလ် ဖြည့်သွင်းရန် လိုအပ်ပါသည်။',
            'email.email' => 'မှန်ကန်သော အီးမေးလ်ပုံစံ ဖြစ်ရပါမည်။',
            'password.required' => 'စကားဝှက်ကို ဖြည့်သွင်းရန် လိုအပ်ပါသည်။',
        ];
    }
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    // public function authenticate(): void
    // {
    //     $this->ensureIsNotRateLimited();

    //     if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
    //         RateLimiter::hit($this->throttleKey());

    //         // throw ValidationException::withMessages([
    //         //     'email' => trans('auth.failed'),
    //         // ]);
    //
    //     }

    //     RateLimiter::clear($this->throttleKey());
    // }
    public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    $user = \App\Models\User::where('email', $this->email)->first();

    if (!$user) {
        RateLimiter::hit($this->throttleKey());
        throw ValidationException::withMessages([
            'email' => 'ဤအီးမေးလ်ဖြင့် အကောင့်ဖွင့်ထားခြင်း မရှိပါ။',
        ]);
    }

    if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'password' => 'လျှို့ဝှက်နံပါတ် မှားယွင်းနေပါသည်။',
        ]);
    }


    RateLimiter::clear($this->throttleKey());
}

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    // public function ensureIsNotRateLimited(): void
    // {
    //     if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
    //         return;
    //     }

    //     event(new Lockout($this));

    //     $seconds = RateLimiter::availableIn($this->throttleKey());

    //     throw ValidationException::withMessages([
    //         'email' => trans('auth.throttle', [
    //             'seconds' => $seconds,
    //             'minutes' => ceil($seconds / 60),
    //         ]),
    //     ]);
    // }

    public function ensureIsNotRateLimited(): void
{
    if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
        return;
    }

    event(new Lockout($this));

    $seconds = RateLimiter::availableIn($this->throttleKey());

    throw ValidationException::withMessages([
        'email' => "အကြိမ်ကြိမ် မှားယွင်းနေပါသည်။ ကျေးဇူးပြု၍ $seconds စက္ကန့် စောင့်ပြီးမှ ပြန်ကြိုးစားပါ။",
    ]);
}

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}

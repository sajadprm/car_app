<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class RegisterController extends Controller  
{
    /**
     * Register Api 
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'mobile' => 'required|starts_with:09|size:11|unique:users,mobile',
            'national_code' =>'required|unique:users,national_code|size:10',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }       

        $input = $request->all();
        $user = User::create($input);
        $success['token'] = $user->createToken('CarApp')->plainTextToken; 
        $success['mobile'] = $user->mobile;
        $success['national_code'] = $user->national_code;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // اعتبارسنجی ورودی‌ها
        $validator = FacadesValidator::make($request->all(), [
            'mobile' => 'required|starts_with:09|size:11',
            'otp' => 'required|size:6|numeric', // فرض کنید کد OTP یک کد ۶ رقمی است
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    
        // پیدا کردن کاربر با شماره موبایل
        $user = User::where('mobile', $request->mobile)->first();
    
        if (!$user) {
            return $this->sendError('User not found.', ['error' => 'User not found']);
        }
    
        // بررسی صحت کد OTP
        if ($request->otp === $user->otp) { // فرض کنید کد OTP در دیتابیس ذخیره شده
            // ایجاد توکن برای کاربر
            $success['token'] = $user->createToken('CarApp')->plainTextToken; 
            $success['mobile'] = $user->mobile;
    
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Invalid OTP.', ['error' => 'Invalid OTP']);
        }
    }
    
}

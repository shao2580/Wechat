<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = session('admin');
        // $data = json_decode($data,true);
        // dump($data);
        if (!$data) {
            return redirect('/login');
        }
        
        $name =$data[0]->name;
        $password = $data[0]->password;
        $admin = DB::table('admin')->where(['name'=>$name,'password'=>$password])->get()->toArray();
        // $admin = json_decode($admin,true);
        // dd($admin);
        if (in_array($admin,$data)){
            return redirect('/login');
        }
        return $next($request);
    }
}

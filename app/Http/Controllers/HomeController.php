<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Service;


class HomeController extends Controller
{
public function index(){
$services = Service::latest()->take(6)->get();
$promos = [
['title'=>'Promo 1','desc'=>'Diskon 20% untuk cucian pertama'],
['title'=>'Promo 2','desc'=>'Gratis pengambilan di area tertentu']
];
return view('home', compact('services','promos'));
}


public function about(){
return view('about');
}


public function contact(){
return view('contact');
}


public function promos(){
return view('promos');
}
}
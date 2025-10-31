<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Service;


class ServiceController extends Controller
{
public function __construct(){
// admin-only for create/update/delete via middleware on routes
}


public function index(){
$services = Service::all();
return view('services.index', compact('services'));
}


public function create(){
return view('admin.services.create');
}


public function store(Request $r){
$r->validate(['title'=>'required','price'=>'required|numeric']);
Service::create($r->only('title','description','price'));
return redirect()->route('services.index')->with('success','Service added');
}


public function edit(Service $service){
return view('admin.services.edit', compact('service'));
}


public function update(Request $r, Service $service){
$r->validate(['title'=>'required','price'=>'required|numeric']);
$service->update($r->only('title','description','price'));
return redirect()->route('services.index')->with('success','Service updated');
}


public function destroy(Service $service){
$service->delete();
return back()->with('success','Deleted');
}
}
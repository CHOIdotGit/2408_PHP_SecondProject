<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {
        $parent = Auth::guard('parents')->user();
        $parentHome = Child::select('children.child_id', 'children.name', 'children.nick_name', 'children.profile')
                                    ->where('children.parent_id', $parent->parent_id)
                                    ->get();
    
    
        foreach($parentHome as $child) {
            $child->setRelation('missions', $child->missions()->latest()->where('missions.status', 1)->limit(3)->get());
            $child->setRelation('transactions', $child->transactions()->latest()->limit(3)->get());
        }
    
        $responseData = [
            'success' => true
            ,'msg' => '홈페이지 로드 성공공'
            ,'parentHome' => $parentHome->toArray()
        ];
        return response()->json($responseData, 200);
    }
}

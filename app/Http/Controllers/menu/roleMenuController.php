<?php

namespace App\Http\Controllers\menu;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Role_menu;
use App\Models\Menu;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class roleMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Data Role Menu Aplikasi';
        $data['rs_role'] = Role::paginate(10);
        return view('roleMenu.index', $data);
    }

    public function listDataRoleMenu($role_id)
    {
        $detail = Role::where('role_id', $role_id)->first();
        if (empty($detail)) {
            return redirect()->route('roleMenu')->with('error', 'Data tidak ditemukan');
        }
        $data['title'] = 'Data Role Menu Aplikasi';
        $data['detail'] = $detail;
        // all menu

        $rs_menu = Menu::with('role_menu')->whereRelation('role_menu', 'role_id', Auth::user()->role_id)->get();
        $tes = Menu::select('app_menu.menu_id', 'menu_name', 'app_heading_id', 'role_menu_id')->with('heading')->leftJoin('app_role_menu', function($join) use($detail) {
            $join->on('app_menu.menu_id', '=', 'app_role_menu.menu_id')
            ->where('app_role_menu.role_id', '=', $detail->role_id);
          })->orderBy('app_heading_id', 'ASC')->get();
        $data['rs_role_menu'] = $tes;
        // dd($tes);
        // dd($data);
        return view('roleMenu.listDataRole', $data);
    }

    public function tambahRoleMenu(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'role_id'     => 'required',
            'status'   => 'required',
            'menu_id'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            // return response()->json($validator->errors(), 422);
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400);
        }
        // check
        $detail = Role::where('role_id', $request->role_id)->first();
        if (empty($detail)) {
            return redirect()->route('roleMenu')->with('error', 'Data tidak ditemukan');
        }
        // ubah role menu
        $status = $request->status;
        if ($status == 'tambah') {
            $role_menu_id = $this->last_role_menu_id();
            Role_menu::create([
                'role_menu_id' => $role_menu_id,
                'menu_id' => $request->menu_id,
                'role_id' => $request->role_id,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan!',
            ]);
        } else {
            $detail = Role_menu::where('menu_id', $request->menu_id)->where('role_id', $request->role_id)->first();
            if (empty($detail)) {
                return redirect()->route('roleMenu')->with('error', 'Data tidak ditemukan');
            }
            $detail->delete();
            //
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil dihapus!',
            ]);
        }

    }

    function last_role_menu_id() {
        // get last data
        $last_data = Role_menu::select('role_menu_id')->orderBy('role_menu_id', 'DESC')->first();
        $last_number = substr($last_data->role_menu_id, 2, 6) + 1;
        $zero = '';
        for ($i=strlen($last_number); $i <=4; $i++) {
            $zero .= '0';
        }
        $new_id = 'RM'.$zero.$last_number;
        //
        return $new_id;
    }

}

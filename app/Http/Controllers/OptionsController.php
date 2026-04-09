<?php

namespace App\Http\Controllers;
use App\Models\Options;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    
    /**
     * Branding Index
     */
    public function bradingIndex()
    {
        $branding_option_list = Options::where('option_name', 'branding_options')->first();
        $branding_option_arr = [];
        if( isset($branding_option_list->option_value) && !empty($branding_option_list->option_value) ){
            $branding_option_arr = unserialize($branding_option_list->option_value);
        }
        return view('branding.index', [ 'branding_option_arr' => $branding_option_arr ]);
    }

    public function UploadMedia( Request $request ){
        try{    
            $user_id = auth()->user()->id;
            $request->validate([
                'file' => 'required|file'
            ]);
            
            $media = CommanUploadMedia($request->file, $user_id, 'uploads/branding/'.$user_id, 'unique');
            return response()->json([
                'message' => 'Store Attachement Successfully',                
                'media' => $media,                
                'status' => 'success',                
            ],200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],500);
        }  
    }


    public function RemoveMedia( Request $request ){
        try{    
            $user_id = auth()->user()->id;
            $media_id = $request->get('media_id');

            $result = CommanRemoveMedia( $media_id , $user_id);
            if( $result == true ){
                return response()->json([
                    'message' => 'Attachement Remove Successfully',                
                    'status' => 'success',                
                ],200);    
            }else{
                return response()->json([
                    'message' => 'Attachement Not Remove Successfully',                
                    'status' => 'fail',                
                ],200); 
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],500);
        }  
    }

    /**
     * Add Or Update Branding
     */
    public function AddOrUpdateBranding( Request $request ){
        try{    
            $user_id = auth()->user()->id;
            $branding_options = $request->get('branding_options');
            $branding_option_arr = [];
            if( is_array($branding_options) && count($branding_options) > 0 ){
               $branding_option_arr = $branding_options;
            }
            Options::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'option_name' => 'branding_options',
                ],
                [
                    'option_value' => serialize( $branding_option_arr ),
                ]
            );
            return response()->json([
                'message' => 'Branding Data Added Or Updated Successfully.',                
                'status' => 'success',                
            ],200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],500);
        }  
    }

}

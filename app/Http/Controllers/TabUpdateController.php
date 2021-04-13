<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tab;
use App\Http\Resources\TabResource;


class TabUpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Tab $tab)
    {
        // ToDo: on update get all temp_images records where tab_id matches
        // and simply delete them
        $_PATCH = json_decode(file_get_contents('php://input'), true);
        $request->merge([
            'content' => json_encode($_PATCH['content'])
        ]);
        $request->validate([
            'content' => 'json',
        ]);
        $tab->content = $request->content;
        $tab->save();
        return new TabResource($tab);
    }

    private function filesForDelete($oldContent, $newContent){
        
        $oldContent = !is_array($oldContent) ?: json_decode($oldContent);
        $newContent = !is_array($newContent) ?: json_decode($newContent);
        
        $oldFiles = [];
        $newFiles = [];
        foreach ($oldContent->blocks as $block){
            if($block->type == 'image'){
                $oldFiles[] = $block->data->file->url;
            }
        }
        foreach ($newContent->blocks as $block){
            if($block->type == 'image'){
                $newFiles[] = $block->data->file->url;
            }
        }

        $toDelete = array_values(array_diff($oldFiles, $newFiles));
        if(count($toDelete) > 0){
            foreach($toDelete as $fileUrl){
                //somehow get path from url and delete
            }
        }
    }
}

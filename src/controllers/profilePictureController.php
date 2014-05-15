<?php

class ProfilePictureController extends BaseController {

    /**
     * Upload a picture to the correct folder.
     * 
     * 
     * @return Void
     */
    public function upload() {
        try {
            $folderPath = Auth::user()->getFolderPath();
            $settings = Auth::user()->getFileSettings();
            //check that form has a file
            if(!Input::hasFile("profile_picture")){
                return Redirect::back();
            }
            //Check for limit before continue
            if ($settings['size'] >= 5) {
                return Redirect::back()->withErrors(array(
                            "picture_number" =>
                            "You cannot have more than five stored profile pictures. "
                            . "You can remove some if you want to."));
            }
            //Ok... you may upload now
            $file = Input::file("profile_picture");
            $validator = Validator::make(
                            Input::all(), array('profile_picture' => 'mimes:jpeg,png,jpg | max: 800')
            );
            //now we check that the file is what we are expecting 
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator);
            }
            //ok I trust you, you are uploading a picture, now lets assign a 
            //name
            $name = Str::random(15);
            //lets look fo something... unique
            while (isset($settings["paths"][$name])) {
                $name = Str::random(15);
            }
            //Now lets do the actual upload
            $file->move($folderPath, $name);
            //ok its where its suppoused to be now, lets report that is there.
            $settings["paths"][$name] = true;
            $settings["size"] ++;
            file_put_contents($folderPath . "/setting.json", json_encode($settings));
            
            //finally save it!
            if (!Auth::user()->localProfile()->getResults()) {
                Auth::user()->localProfile()->create(
                        array('photo_url' => Auth::user()->getPictureUrl($name)));
            } else {
                Auth::user()->localProfile()->
                        update(array('photo_url' => Auth::user()->getPictureUrl($name)));
            }
            return Redirect::back();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function select() {
        try {
            $input = Input::all();
            $validator = Validator::make(
                            $input, array('select' => "alphanum | size:15")
            );
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator);
            }
            $picture = Auth::user()->getPictureUrl($input['select']);
            if ($picture) {
                Auth::user()->localProfile()->
                        update(array('photo_url' => URL::asset($picture)));
            } else {
                return Redirect::back()->withErrors(
                                array("genericError" => "That file does not exits"));
            }
            return Redirect::back();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete() {
        try {
            $input = Input::all();
            $localProfile = Auth::user()->localProfile()->getResults();
            $folderPath = Auth::user()->getFolderPath();
            $settings = Auth::user()->getFileSettings();
            $validator = Validator::make(
                            $input, array('delete' => "alphanum | size:15")
            );
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator);
            }
            $picture = Auth::user()->getPictureUrl($input['delete']);


            if (($picture)) {
                unset($settings["paths"][$input["delete"]]);
                unlink(public_path() . '/' . $folderPath . '/' . $input['delete']);
                $settings["size"] --;
                if ($localProfile->photo_url) {
                    Auth::user()->localProfile->update(array('photo_url' => NULL));
                }
            } else {
                return Redirect::back()->withErrors(
                                array("genericError" => "That file does not exits"));
            }

            file_put_contents($folderPath . "/setting.json", json_encode($settings));
            return Redirect::back();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}

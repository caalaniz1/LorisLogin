<?php

class ProfilePicture extends BaseController {

    /**
     * Upload a picture to the correct folder.
     * 
     * 
     * @return Void
     */
    public function upload() {
        try {
            $folderPath = "uploads/profilePictures/" . Auth::user()->id . '';
            $settings = json_decode(file_get_contents($folderPath . "/setting.json"), true);
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
            //finally save it!
            if (!Auth::user()->localProfile()->getResults()) {
                Auth::user()->localProfile()->create(
                        array('photo_url' => URL::asset("$folderPath/$name")));
            } else {
                Auth::user()->localProfile()->
                        update(array('photo_url' => URL::asset("$folderPath/$name")));
            }
            file_put_contents($folderPath . "/setting.json", json_encode($settings));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($path) {      
        if (!Auth::user()->localProfile()->getResults()) {
            Auth::user()->localProfile()->create(
                    array('photo_url' => $path));
        } else {
            Auth::user()->localProfile()->
                    update(array('photo_url' => $path));
        }
    }

}

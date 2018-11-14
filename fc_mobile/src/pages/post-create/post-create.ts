import { Component } from "@angular/core";
import { IonicPage, NavController, NavParams } from "ionic-angular";
import { UtilsProvider } from "../../providers/utils/utils";

import { AuthProvider } from "../../providers/auth/auth";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
/**
 * Generated class for the PostCreatePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-post-create",
  templateUrl: "post-create.html"
})
export class PostCreatePage {
  text: any;
  user_hash: any;
  user: any;
  callBack: any;
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    private utils: UtilsProvider,
    public auth: AuthProvider,
    public fb: FacebookLoginProvider
  ) {
    this.user = auth.getCurrentUser();
    this.callBack = this.navParams.data;
  }

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.navCtrl.push(HomePage);
    });
  }

  publish() {
    this.utils.rest(
      "users/" +
        Math.random()
          .toString(36)
          .substring(11) +
        "/posts",
      "post",
      true,
      { body: this.text },
      resp => {
        if (this.callBack) {
          this.callBack(resp);
          this.navCtrl.pop();
        }
      },
      undefined
    );
  }
  picker() {
    /*this.actionSheet.create({
      title: 'Sube una imagen o video',
      buttons: [
        {
          text: 'FilePicker',
          handler: () => {
            //https://ionicframework.com/docs/native/file/
            this.file.checkDir(this.file.dataDirectory, 'mydir')
            .then(_ => console.log('Directory exists'))
            .catch(err => console.log('Directory doesn\'t exist'));
          }
        },{
          text: 'ImagePicker',
          handler: () => {
            //https://ionicframework.com/docs/native/image-picker/
            this.imagePicker.getPictures({maximumImagesCount:1,width: 500, 
        height: 500})
            .then((results) => {
              for (var i = 0; i < results.length; i++) {
                this.uploadImage(results[i]);
              }
            }, (err) => {});
          }
        },{
          text: 'Camera',
          handler: () => {
            //https://ionicframework.com/docs/native/camera/
            let options: CameraOptions = {
              quality: 100,
              destinationType: this.camera.DestinationType.FILE_URI,
              encodingType: this.camera.EncodingType.JPEG,
              mediaType: this.camera.MediaType.PICTURE
            }

            this.camera.getPicture(options).then((imageData) => {
             this.uploadImage(imageData);
            }, (err) => { });
          }
        },{
          text: 'Cancelar',
          role: 'cancel',
          handler: () => {}
        }
      ]
    }).present();*/
  }
  //https://ionicframework.com/docs/native/image-resizer/
  //https://ionicframework.com/docs/native/base64/
  //https://golb.hplar.ch/2017/02/Uploading-pictures-from-Ionic-2-to-Spring-Boot.html
  uploadImage(src) {
    /*let t = this;
    this.base64.encodeFile(src).then((base64File: string) => {

      this.file.resolveLocalFilesystemUrl(src)
        .then((file:any) => {
          let reader = new FileReader();
          reader.onloadend = () => {
            let formData = new FormData();
            let imgBlob = new Blob([reader.result], {type: file.type});
            //{file_image:base64File,form_device:'phone'}
            formData.append('file_image', imgBlob, file.name);
            formData.append('form_device','web');
            this.utils.rest('users/'+this.user_hash+'/file-images-upload',
            'post',true,formData,
            (resp)=>{
              console.log(resp);
            },undefined);
          };
          reader.readAsArrayBuffer(file);
        })
        .catch(err => console.log('archivo no existe'));
    }, (err) => {
      console.log(err);
    });*/
  }
}

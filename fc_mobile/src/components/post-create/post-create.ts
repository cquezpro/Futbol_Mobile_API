import { Component } from "@angular/core";
import {
  NavParams,
  ViewController,
  ActionSheetController
} from "ionic-angular";
import { UtilsProvider } from "../../providers/utils/utils";
import { ImagePicker } from "@ionic-native/image-picker";
import { File } from "@ionic-native/file";
import { Camera, CameraOptions } from "@ionic-native/camera";
import { Base64 } from "@ionic-native/base64";
/**
 * Generated class for the PostCreateComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: "post-create",
  templateUrl: "post-create.html"
})
export class PostCreateComponent {
  user_hash: any;
  text: string;

  constructor(
    private utils: UtilsProvider,
    private navCtrl: NavParams,
    private viewCtrl: ViewController,
    private imagePicker: ImagePicker,
    private file: File,
    private camera: Camera,
    private actionSheet: ActionSheetController,
    private base64: Base64
  ) {
    this.user_hash = navCtrl.data.user_hash;
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
        this.viewCtrl.dismiss(resp);
      },
      undefined
    );
  }
  picker() {
    this.actionSheet
      .create({
        title: "Sube una imagen o video",
        buttons: [
          {
            text: "FilePicker",
            handler: () => {
              //https://ionicframework.com/docs/native/file/
              /*this.file.checkDir(this.file.dataDirectory, 'mydir')
            .then(_ => console.log('Directory exists'))
            .catch(err => console.log('Directory doesn\'t exist'));*/
            }
          },
          {
            text: "ImagePicker",
            handler: () => {
              //https://ionicframework.com/docs/native/image-picker/
              this.imagePicker
                .getPictures({
                  maximumImagesCount: 1,
                  width: 500,
                  height: 500
                })
                .then(
                  results => {
                    for (var i = 0; i < results.length; i++) {
                      this.uploadImage(results[i]);
                    }
                  },
                  err => {}
                );
            }
          },
          {
            text: "Camera",
            handler: () => {
              //https://ionicframework.com/docs/native/camera/
              let options: CameraOptions = {
                quality: 100,
                destinationType: this.camera.DestinationType.FILE_URI,
                encodingType: this.camera.EncodingType.JPEG,
                mediaType: this.camera.MediaType.PICTURE
              };

              this.camera.getPicture(options).then(
                imageData => {
                  this.uploadImage(imageData);
                },
                err => {}
              );
            }
          },
          {
            text: "Cancelar",
            role: "cancel",
            handler: () => {}
          }
        ]
      })
      .present();
  }
  //https://ionicframework.com/docs/native/image-resizer/
  //https://ionicframework.com/docs/native/base64/
  //https://golb.hplar.ch/2017/02/Uploading-pictures-from-Ionic-2-to-Spring-Boot.html
  uploadImage(src) {
    let t = this;
    this.base64.encodeFile(src).then(
      (base64File: string) => {
        this.file
          .resolveLocalFilesystemUrl(src)
          .then((file: any) => {
            let reader = new FileReader();
            reader.onloadend = () => {
              let formData = new FormData();
              let imgBlob = new Blob([reader.result], { type: file.type });
              //{file_image:base64File,form_device:'phone'}
              formData.append("file_image", imgBlob, file.name);
              formData.append("form_device", "web");
              this.utils.rest(
                "users/" + this.user_hash + "/file-images-upload",
                "post",
                true,
                formData,
                resp => {
                  console.log(resp);
                },
                undefined
              );
            };
            reader.readAsArrayBuffer(file);
          })
          .catch(err => console.log("archivo no existe"));
      },
      err => {
        console.log(err);
      }
    );
  }
}

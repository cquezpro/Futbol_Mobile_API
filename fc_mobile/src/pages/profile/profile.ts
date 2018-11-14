import { Component } from "@angular/core";
import {
  IonicPage,
  NavController,
  NavParams,
  Nav,
  ActionSheetController
} from "ionic-angular";

import { AuthProvider } from "../../providers/auth/auth";
import { UtilsProvider } from "../../providers/utils/utils";
import { ImagePicker } from "@ionic-native/image-picker";
import { CameraOptions, Camera } from "@ionic-native/camera";
import { PhotoViewer } from "@ionic-native/photo-viewer";
import { IfObservable } from "rxjs/observable/IfObservable";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
/**
 * Generated class for the ProfilePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-profile",
  templateUrl: "profile.html"
})
export class ProfilePage {
  tabs: string = "posts";
  user: any;
  generalInfo: any;
  imgCover: string =
    "http://www.audienciaelectronica.net/wp-content/uploads/2015/12/HACKERS-INTERNET.jpg";
  private posts: any[];
  private follows: any[];
  private progressSections: any[];

  colors: any = {
    0: "#ec6c2f",
    1: "#009245",
    2: "#0071bc",
    3: "#666666",
    4: "#c1272d",
    5: "#f2f2f2",
    6: "#93278f",
    7: "#fbb03b"
  };
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    private auth: AuthProvider,
    private utils: UtilsProvider,
    public imagePicker: ImagePicker,
    public camera: Camera,
    public actionSheetCtrl: ActionSheetController,
    private photoViewer: PhotoViewer,
    public fb: FacebookLoginProvider,
    private nav: Nav
  ) {
    this.user = auth.getCurrentUser();
    console.log(this.user);
    this.utils.rest(
      `users/${this.user.id}`,
      "get",
      true,
      {},
      resp => {
        this.user = resp.user;
        console.log(resp.user);
      },
      err => {
        console.log(err.statusText);
      }
    );

    this.progressSections = [
      { title: "Fan", icon: "hand" },
      { title: "Player", icon: "contact" }
    ];
  }

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.navCtrl.push(HomePage);
    });
  }

  ionViewCanEnter() {
    return this.auth.isLogged();
  }

  openImage(img) {
    this.photoViewer.show(img, "", { share: true });
  }

  openOptionsCover() {
    const actionSheet = this.actionSheetCtrl.create({
      title: "",
      buttons: [
        {
          text: "Ver portada de perfil",
          role: "show_profile_image",
          icon: "person",
          handler: () => {
            this.openImage(this.user.cover.cover_path);
          }
        },
        {
          text: "Subir Imagen",
          role: "change_cover_image",
          icon: "cloud-upload",
          handler: () => {
            const options: CameraOptions = {
              quality: 100,
              destinationType: this.camera.DestinationType.DATA_URL,
              sourceType: this.camera.PictureSourceType.PHOTOLIBRARY,
              saveToPhotoAlbum: false,
              allowEdit: true
            };

            this.camera.getPicture(options).then(
              imageData => {
                let base64Image = "data:image/jpeg;base64," + imageData;
                let url = `users/${this.user.id}/cover`;

                if (this.user.cover === null)
                  this.user.cover = {
                    cover: {
                      id: 0,
                      cover_path: ""
                    }
                  };
                this.utils.rest(
                  url,
                  "post",
                  true,
                  {
                    file_cover: base64Image,
                    type_sender: "base64",
                    form_device: "mobile"
                  },
                  resp => {
                    this.user.cover = resp.data[0];
                  },
                  err => {
                    alert(`Error: ${err}`);
                  }
                );
              },
              err => {}
            );
          }
        }
      ]
    });

    actionSheet.present();
  }

  openOptionsAvatar() {
    const actionSheet = this.actionSheetCtrl.create({
      title: "",
      buttons: [
        {
          text: "Ver foto de perfil",
          role: "show_profile_image",
          icon: "person",
          handler: () => {
            this.openImage(this.user.avatar.avatar_path);
          }
        },
        {
          text: "Tomar una foto",
          role: "take_a_picture",
          icon: "camera",
          handler: () => {
            const options: CameraOptions = {
              quality: 100,
              destinationType: this.camera.DestinationType.DATA_URL,
              encodingType: this.camera.EncodingType.JPEG,
              mediaType: this.camera.MediaType.PICTURE,
              saveToPhotoAlbum: false,
              allowEdit: true
            };

            this.camera.getPicture(options).then(
              imageData => {
                // imageData is either a base64 encoded string or a file URI
                // If it's base64 (DATA_URL):
                let base64Image = "data:image/jpeg;base64," + imageData;
                this.saveAvatar(base64Image);
              },
              err => {}
            );
          }
        },
        {
          text: "Seleccionar foto de perfil",
          role: "change_profile_avatar",
          icon: "images",
          handler: () => {
            const options: CameraOptions = {
              quality: 100,
              destinationType: this.camera.DestinationType.DATA_URL,
              sourceType: this.camera.PictureSourceType.PHOTOLIBRARY,
              saveToPhotoAlbum: false,
              allowEdit: true
            };

            this.camera.getPicture(options).then(
              imageData => {
                let base64Image = "data:image/jpeg;base64," + imageData;
                this.saveAvatar(base64Image);
              },
              err => {}
            );
          }
        }
      ]
    });
    actionSheet.present();
  }

  saveAvatar(base64Image) {
    if (this.user.avatar === null)
      this.user.avatar = {
        avatar: {
          id: 0,
          avatar_path: ""
        }
      };

    this.user.avatar.avatar_path = base64Image;
    this.utils.rest(
      `users/${this.user.id}/avatar`,
      "post",
      true,
      {
        file_avatar: base64Image,
        type_sender: "base64",
        form_device: "mobile"
      },
      resp => {
        this.user.avatar = resp.data[0];
      },
      err => {
        alert(`Error: ${err}`);
      }
    );
  }

  ionViewDidLoad() {
    let t = this;
    /* this.utils.rest('users/'+this.user.hash_id+'/posts','get',false,undefined,
		(resp)=>{
			t.posts = resp;
		},undefined); */
    this.utils.rest(
      "followings",
      "get",
      false,
      undefined,
      resp => {
        console.log(resp);
      },
      undefined
    );
  }
  openMoreOptions() {
    const actionSheet = this.actionSheetCtrl.create({
      buttons: [
        {
          text: "Editar perfil Jugador",
          role: "edit_player_profile",
          icon: "create",
          handler: () => {
            this.nav.push("EditPlayerProfilePage").catch(resp => {
              console.log(resp);
            });
          }
        },
        {
          text: "Editar perfil Staff",
          role: "edit_staff_profile",
          icon: "create",
          handler: () => {
            this.nav.push("EditStaffProfilePage");
          }
        },
        {
          text: "Cancel",
          role: "cancel",
          icon: "close",
          handler: () => {}
        }
      ]
    });
    actionSheet.present();
  }

  edit() {
    this.nav.push("ProfileEditPage");
  }
}

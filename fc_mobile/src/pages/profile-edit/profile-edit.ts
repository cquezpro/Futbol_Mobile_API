import { Component } from "@angular/core";
import {
  IonicPage,
  NavController,
  NavParams,
  Toast,
  DateTime,
  ModalController
} from "ionic-angular";
import { AuthProvider } from "../../providers/auth/auth";
import { UtilsProvider } from "../../providers/utils/utils";
import { ActionSheetController } from "ionic-angular";
import { ImagePicker } from "@ionic-native/image-picker";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
/**
 * Generated class for the ProfileEditPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-profile-edit",
  templateUrl: "profile-edit.html"
})
export class ProfileEditPage {
  user: any;
  path: String;
  userBasicInfo: any;
  biography: String = "";
  dateOfbirthday: String;
  minYear: String;
  cityOfResident: String;
  cityOfResidenceId: any;
  generalInfo: any;

  constructor(
    public navCtrl: NavController,
    private auth: AuthProvider,
    private utils: UtilsProvider,
    public imagePicker: ImagePicker,
    public actionSheetCtrl: ActionSheetController,
    public modalCtrl: ModalController,
    public fb: FacebookLoginProvider
  ) {
    let today = new Date();
    let toYear = today.getFullYear();

    this.minYear = (toYear - 13).toString();

    this.user = this.auth.getCurrentUser();

    this.path = "assets/imgs/fc-logo/Logo.png";
    this.loadData();
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

  openModalCitiy() {
    const modal = this.modalCtrl.create("ModalCountrySearchPage", {
      type: "cities"
    });

    modal.onDidDismiss(data => {
      if (data) {
        this.cityOfResident = data.name;
        this.cityOfResidenceId = data.id;
      }
    });
    modal.present();
  }

  saveBasicInfo() {
    let url = `users/${this.user.id}/update-biography`;

    this.userBasicInfo = {
      first_name: this.user.first_name,
      last_name: this.user.last_name,
      nick_name: this.user.nick_name,
      biography: this.biography
    };

    this.utils.rest(
      url,
      "put",
      true,
      this.userBasicInfo,
      resp => {
        this.auth.setCurrentUser(resp.user);
      },
      err => {
        alert(err);
      }
    );
  }

  saveGeneralInfo() {
    let url = `users/${this.user.id}/general-information`;
    this.generalInfo = {
      birthday: this.dateOfbirthday,
      city_of_residence: this.cityOfResidenceId
    };

    this.utils.rest(
      url,
      "post",
      true,
      this.generalInfo,
      resp => {
        this.auth.setCurrentUser(resp.user);
      },
      err => {
        alert(err);
      }
    );
  }

  loadData() {
    this.utils.rest(
      `users/${this.user.id}`,
      "get",
      true,
      {},
      resp => {
        this.user = resp.user;
        this.userBasicInfo = this.user;

        this.biography =
          this.user.generalInformation !== null
            ? this.user.generalInformation.biography
            : "";

        this.dateOfbirthday =
          this.user.generalInformation !== null
            ? this.user.generalInformation.birthday
            : "";

        this.cityOfResident =
          this.user.generalInformation !== null
            ? this.user.generalInformation.city_of_residence !== null
              ? this.user.generalInformation.city_of_residence.name
              : null
            : null;

        this.cityOfResidenceId =
          this.user.generalInformation !== null
            ? this.user.generalInformation.city_of_residence !== null
              ? this.user.generalInformation.city_of_residence.id
              : null
            : null;
      },
      err => {
        console.log(err);
      }
    );
  }
  goBack() {
    this.navCtrl.pop();
  }
}

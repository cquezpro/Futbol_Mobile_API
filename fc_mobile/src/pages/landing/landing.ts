import { Component } from "@angular/core";
import {
  NavController,
  NavParams,
  Nav,
  Platform,
  PopoverController
} from "ionic-angular";
import { YoutubeVideoPlayer } from "@ionic-native/youtube-video-player";
import { AuthProvider } from "../../providers/auth/auth";
import { HomePage } from "../home/home";
import { PopoverNotificationsComponent } from "../../components/popover-notifications/popover-notifications";
import { PopoverMessagesComponent } from "../../components/popover-messages/popover-messages";
import { RegisterPage } from "../register/register";
import { UtilsProvider } from "../../providers/utils/utils";

/**
 * Generated class for the LandingPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@Component({
  selector: "page-landing",
  templateUrl: "landing.html"
})
export class LandingPage {
  tab1Root = PopoverNotificationsComponent;
  tab2Root = PopoverMessagesComponent;
  tab4Root = RegisterPage;
  notifications: any;

  user: null;
  constructor(
    public navCtrl: NavController,
    public nav: Nav,
    public navParams: NavParams,
    public youtube: YoutubeVideoPlayer,
    private auth: AuthProvider,
    public popoverCtrl: PopoverController,
    public utils: UtilsProvider,
    public platform: Platform
  ) {
    this.user = this.auth.getCurrentUser();
    this.notifications = 0;
  }

  ionViewDidLoad() {
    if (!this.auth.isLogged) {
      this.logOut();
    }

    this.loadNotifications();
    this.addTabAttribute();
  }

  loadNotifications() {
    let url = "notifications";

    this.utils.rest(
      url,
      "get",
      false,
      {},
      resp => {
        console.log(resp);
        this.notifications = resp.total;
      },
      err => {}
    );
  }

  //TODO: borrar
  playVideo(e) {
    this.youtube.openVideo("ow0IG8UwZOE");
  }

  logOut() {
    this.auth.logOut();
    this.navCtrl.push(HomePage);
  }

  addTabAttribute() {
    // let arialabel = document.getElementsByClassName(".tabs-md .tab-button[aria-selected=true] .tab-button-icon")[0].getAttribute("aria-label");
    let arialabel = document.getElementsByClassName("tab-button-icon");
    for (let i = 0; i < arialabel.length; i++) {
      let beforevar = window
        .getComputedStyle(
          document.querySelector(
            ".ion-md-" + arialabel[i].getAttribute("aria-label")
          ),
          ":before"
        )
        .getPropertyValue("content");

      arialabel[i].setAttribute("data-test", beforevar.replace(/"/g, ""));
    }
  }
}

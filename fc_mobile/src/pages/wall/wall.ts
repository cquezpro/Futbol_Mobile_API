import { Component, NgModule } from "@angular/core";
import { IonicPage, NavController, NavParams, Nav } from "ionic-angular";

import { AuthProvider } from "../../providers/auth/auth";
import { UtilsProvider } from "../../providers/utils/utils";

import * as xml2js from "xml2js";
import { Globalization } from "@ionic-native/globalization";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
import { HomePage } from "../home/home";
import { LandingPage } from "../landing/landing";

/**
 * Generated class for the WallPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: "page-wall",
  templateUrl: "wall.html"
})
export class WallPage {
  posts: any[];
  user: any;
  suggestion: any[];
  news: any[];
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    private utils: UtilsProvider,
    private auth: AuthProvider,
    private fb: FacebookLoginProvider,
    public globalization: Globalization,
    private nav: Nav
  ) {
    this.globalization
      .getPreferredLanguage()
      .then(res => console.log(res))
      .catch(e => console.log(e));

    this.user = this.auth.getCurrentUser();
    if (this.user) {
      this.utils.rest(
        `users/${this.user.id}`,
        "get",
        true,
        {},
        resp => {
          this.user = resp.user;
          this.auth.setCurrentUser(this.user);
        },
        err => {}
      );
    } else {
      this.nav.setRoot(LandingPage);
      window.location.reload;
    }
  }

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.nav.setRoot(LandingPage);
      this.navCtrl.push(HomePage);
    });
  }

  doRefresh(refresher) {
    this.loadData().then(r => {
      setTimeout(() => {
        refresher.complete();
      }, 10);
    });
  }

  centeredSlides() {
    return true;
  }

  getUserAvatar(user) {
    if (user.avatar !== null) return `url(${user.avatar.avatar_path})`;

    return "";
  }

  loadData() {
    return new Promise(resolve => {
      let t = this;
      this.utils.rest(
        "users/" + this.auth.getCurrentUser().id + "/posts?type=all",
        "get",
        false,
        undefined,
        resp => {
          t.posts = resp;
          resolve();
        },
        undefined
      );
    });
  }

  loadSugesstions() {
    this.utils.rest(
      "suggestions",
      "get",
      false,
      undefined,
      resp => {
        this.suggestion = resp.users;
      },
      undefined
    );
  }

  ionViewDidLoad() {
    this.loadData();
    this.loadSugesstions();

    this.utils.rssRead("https://www.fifa.com/theclub/news/rss.xml", resp => {
      xml2js.parseString(resp, (err, result) => {
        this.news = result.rss.channel[0].item;
      });
      //console.log(resp);
    });
  }

  newPost(post) {
    this.posts.unshift(post);
  }

  deletedPost(hash) {
    let index = this.posts.findIndex(x => x.hash_id === hash);
    this.posts.splice(index, 1);
  }

  addUser(id) {
    this.utils.rest(
      "users/" + id + "/friends-send",
      "post",
      true,
      undefined,
      resp => {
        this.utils.toast("Solicitud de amistad enviada");
      },
      undefined
    );
  }

  chatUser(id) {
    this.utils.rest(
      "users/" + id + "/conversations",
      "post",
      true,
      undefined,
      resp => {
        this.nav.push("MessagesPage", { id: id });
      },
      undefined
    );
  }

  createNewPost() {
    this.nav.push("PostCreatePage", post => {
      this.posts.unshift(post);
    });
  }
}

import { Component, ViewChild } from "@angular/core";
import { Platform, Nav } from "ionic-angular";
import { StatusBar } from "@ionic-native/status-bar";
import { SplashScreen } from "@ionic-native/splash-screen";

import { HomePage } from "../pages/home/home";
import { LandingPage } from "../pages/landing/landing";

import { AuthProvider } from "../providers/auth/auth";

import { TranslateService } from "@ngx-translate/core";

import { Globalization } from "@ionic-native/globalization";

@Component({
  templateUrl: "app.html"
})
export class MyApp {
  @ViewChild(Nav)
  nav: Nav;
  rootPage: any = null;
  constructor(
    platform: Platform,
    statusBar: StatusBar,
    splashScreen: SplashScreen,
    public auth: AuthProvider,
    public globalization: Globalization,
    translate: TranslateService
  ) {
    //todo agregar la validacion de los otros idiomas
    this.globalization
      .getPreferredLanguage()
      .then(res => console.log(res))
      .catch(e => console.log(e));

    translate.setDefaultLang("es");

    // desactivar el backbutton
    platform.registerBackButtonAction(() => {}, 1);

    this.rootPage = auth.isLogged() ? LandingPage : HomePage;
    console.clear();
    console.log("Entro en el app.component.ts");
    platform.ready().then(() => {
      statusBar.styleDefault();
      splashScreen.hide();
    });
  }
}

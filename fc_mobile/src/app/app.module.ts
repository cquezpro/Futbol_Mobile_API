import { NgModule, ErrorHandler } from "@angular/core";
import { BrowserModule } from "@angular/platform-browser";
import { IonicApp, IonicModule, IonicErrorHandler } from "ionic-angular";
import { MyApp } from "./app.component";

import { ContactPage } from "../pages/contact/contact";
import { HomePage } from "../pages/home/home";
import { LandingPage } from "../pages/landing/landing";

import { StatusBar } from "@ionic-native/status-bar";
import { SplashScreen } from "@ionic-native/splash-screen";
import { HttpClientModule, HttpClient } from "@angular/common/http";
import { YoutubeVideoPlayer } from "@ionic-native/youtube-video-player";
import { Facebook } from "@ionic-native/facebook";
import { ImagePicker } from "@ionic-native/image-picker";
import { ImageResizer } from "@ionic-native/image-resizer";
import { File } from "@ionic-native/file";
import { Camera } from "@ionic-native/camera";

import { Base64 } from "@ionic-native/base64";

import { AuthProvider } from "../providers/auth/auth";
import { GlobalProvider } from "../providers/global/global";

import { TranslateModule, TranslateLoader } from "@ngx-translate/core";
import { TranslateHttpLoader } from "@ngx-translate/http-loader";

import { PopoverNotificationsComponent } from "../components/popover-notifications/popover-notifications";
import { PopoverMessagesComponent } from "../components/popover-messages/popover-messages";
import { PopoverButtonsComponent } from "../components/popover-buttons/popover-buttons";
import { PostCreateComponent } from "../components/post-create/post-create";

import { ComponentsModule } from "../components/components.module";
import { UtilsProvider } from "../providers/utils/utils";
import { FacebookLoginProvider } from "../providers/facebook-login/facebook-login";
import { ImageResizeProvider } from "../providers/image-resize/image-resize";
import { PusherProvider } from "../providers/pusher/pusher";
import { Globalization } from "@ionic-native/globalization";
import { PhotoViewer } from "@ionic-native/photo-viewer";
import { Crop } from "@ionic-native/crop";

export function setTranslateLoader(http: HttpClient) {
  return new TranslateHttpLoader(http, "./assets/i18n/", ".json");
}

@NgModule({
  declarations: [
    MyApp,
    ContactPage,
    HomePage,
    LandingPage,
    PopoverMessagesComponent,
    PopoverNotificationsComponent,
    PopoverButtonsComponent,
    PostCreateComponent
  ],
  imports: [
    HttpClientModule,
    BrowserModule,
    ComponentsModule,
    IonicModule.forRoot(MyApp),
    TranslateModule.forRoot({
      loader: {
        provide: TranslateLoader,
        useFactory: setTranslateLoader,
        deps: [HttpClient]
      }
    })
  ],
  bootstrap: [IonicApp],
  entryComponents: [
    MyApp,
    ContactPage,
    HomePage,
    LandingPage,
    PopoverMessagesComponent,
    PopoverNotificationsComponent,
    PopoverButtonsComponent,
    PostCreateComponent
  ],
  providers: [
    StatusBar,
    YoutubeVideoPlayer,
    SplashScreen,
    { provide: ErrorHandler, useClass: IonicErrorHandler },
    AuthProvider,
    GlobalProvider,
    Globalization,
    Facebook,
    UtilsProvider,
    FacebookLoginProvider,
    ImagePicker,
    File,
    Crop,
    PhotoViewer,
    Camera,
    ImageResizer,
    Base64,
    ImageResizeProvider,
    PusherProvider
  ]
})
export class AppModule {}

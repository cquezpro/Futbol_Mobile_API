import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { GlobalProvider } from "../global/global";
import { AuthProvider } from "../auth/auth";
import Pusher from "pusher-js";

/*
  Generated class for the PusherProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class PusherProvider {
  channel;
  pusher: any;
  constructor(private global: GlobalProvider, private auth: AuthProvider) {
    this.pusher = new Pusher("9858a69f8ae81ab92eb1", {
      cluster: "us2",
      encrypted: true,
      authEndpoint: global.getServerApi() + "pusher/auth",
      auth: {
        headers: {
          Authorization: "Bearer " + this.auth.getCurrentToken()
        }
      }
    });
  }

  public subscribe(channel) {
    return this.pusher.subscribe(`${channel}`);
  }

  public init() {
    return this.channel;
  }
}

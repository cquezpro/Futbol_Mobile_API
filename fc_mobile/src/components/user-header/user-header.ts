import { Component, Input } from '@angular/core';
import { PopoverController , NavController } from 'ionic-angular';

import { PopoverNotificationsComponent} from "../../components/popover-notifications/popover-notifications";
import { PopoverMessagesComponent} from '../../components/popover-messages/popover-messages';

import {LandingPage} from '../../pages/landing/landing'; 
/**
 * Generated class for the UserHeaderComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: 'user-header',
  templateUrl: 'user-header.html'
})
export class UserHeaderComponent {
  @Input() isProfile: boolean = false;
  @Input() isLanding: boolean = false;
  landing: any = LandingPage;
  toggleSearch = "true";
  constructor(public popover: PopoverController,public nav: NavController) {

  }
  notifications(evt){
    let popover = this.popover.create(PopoverNotificationsComponent);
    popover.present({ev:evt});
    
  }
  messages(evt){
  	let popover = this.popover.create(PopoverMessagesComponent);
  	popover.present({ev:evt});
  }

}

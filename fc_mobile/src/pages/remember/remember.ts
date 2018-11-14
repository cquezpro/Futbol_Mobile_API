import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';

import { UtilsProvider } from '../../providers/utils/utils'
/**
 * Generated class for the RememberPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-remember',
  templateUrl: 'remember.html',
})
export class RememberPage {
  email: any = '';
  constructor(
    public navCtrl: NavController, 
    public navParams: NavParams,
    private utils: UtilsProvider) {
  	
  }
  recover(){
    this.utils.rest('','post',true,{email: this.email},(resp)=>{

    },undefined);
  }
}

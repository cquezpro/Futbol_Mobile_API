import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
/**
 * Generated class for the ProfileInitialPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-profile-initial',
  templateUrl: 'profile-initial.html',
})
export class ProfileInitialPage {
  private interest;
  constructor(public navCtrl: NavController, public navParams: NavParams) {
  	this.interest = [
  		{title: 'news'},
  		{title: 'transfers'},
  		{title: 'proteams'},
  		{title: 'tournaments'},
  		{title: 'fans'},
  		{title: 'uniforms'},
  		{title: 'education'},
  		{title: 'marketing'},
  		{title: 'futfem'}
  	];
  }
}

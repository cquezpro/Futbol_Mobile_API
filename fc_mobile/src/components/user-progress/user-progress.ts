import { Component } from '@angular/core';

/**
 * Generated class for the UserProgressComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: 'user-progress',
  templateUrl: 'user-progress.html'
})
export class UserProgressComponent {

  text: string;

  constructor() {
  }
  doSomethingWithCurrentValue(event){
    console.log(event)
  }

}

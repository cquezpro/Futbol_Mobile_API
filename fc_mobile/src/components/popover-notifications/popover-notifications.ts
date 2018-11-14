import { Component } from '@angular/core';

/**
 * Generated class for the PopoverNotificationsComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: 'popover-notifications',
  templateUrl: 'popover-notifications.html'
})
export class PopoverNotificationsComponent {

  text: string;

  constructor() {
    console.log('Hello PopoverNotificationsComponent Component');
    this.text = 'Hello World';
  }

}

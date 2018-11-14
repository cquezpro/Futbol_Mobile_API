import { Component } from "@angular/core";
import { NavController } from "ionic-angular";
import { ViewChild } from "@angular/core";
import { Slides } from "ionic-angular";

@Component({
  selector: "page-home",
  templateUrl: "home.html"
})
export class HomePage {
  public slidesOptions = {
    paginationType: "fraction"
  };

  constructor(public navCtrl: NavController) {}

  @ViewChild(Slides)
  slides: Slides;

  ngAfterViewInit() {
    // this.slides.lockSwipeToPrev(true)
  }

  /*slideChanged(){
    const _vm = this;
   
    if(!_vm.slides.isEnd() && !_vm.slides.isBeginning()){
      _vm.slides.lockSwipes(false);
    }    
    if(_vm.slides.isEnd()){      
        _vm.slides.lockSwipeToNext(true); 
    }
    if(_vm.slides.isBeginning()){      
        _vm.slides.lockSwipeToPrev(true)        
    }
  }*/
}

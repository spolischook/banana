import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DiscoverHashTagComponent } from './discover-hash-tag.component';

describe('DiscoverHashTagComponent', () => {
  let component: DiscoverHashTagComponent;
  let fixture: ComponentFixture<DiscoverHashTagComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DiscoverHashTagComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DiscoverHashTagComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DashboardMainMenuComponent } from './dashboard-main-menu.component';

describe('DashboardMainMenuComponent', () => {
  let component: DashboardMainMenuComponent;
  let fixture: ComponentFixture<DashboardMainMenuComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DashboardMainMenuComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DashboardMainMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

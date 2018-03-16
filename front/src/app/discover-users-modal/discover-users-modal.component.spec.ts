import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DiscoverUsersModalComponent } from './discover-users-modal.component';

describe('DiscoverUsersModalComponent', () => {
  let component: DiscoverUsersModalComponent;
  let fixture: ComponentFixture<DiscoverUsersModalComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DiscoverUsersModalComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DiscoverUsersModalComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

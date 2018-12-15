import React, { Component } from 'react';
import {Input, Row, Col, NavItem, Navbar, Icon} from 'react-materialize'
// import logo from './logo.svg';
// import './App.css';

class App extends Component {
  render() {
    return (
      <div>
      <Navbar brand='logo' right>
        <NavItem href='get-started.html'><Icon>search</Icon></NavItem>
        <NavItem href='get-started.html'><Icon>view_module</Icon></NavItem>
        <NavItem href='get-started.html'><Icon>refresh</Icon></NavItem>
        <NavItem href='get-started.html'><Icon>more_vert</Icon></NavItem>
      </Navbar>

          <Row>
             <Col s={1} className='grid-example'>1
            <Input placeholder="Placeholder" s={6} label="First Name" />
            <Input s={6} label="Last Name" />
            <Input type="email" label="Email" s={12} />
            </Col>
        </Row>
        </div>
    );
  }
}

export default App;

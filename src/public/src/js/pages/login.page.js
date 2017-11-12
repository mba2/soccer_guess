import React from 'react';
import Logo from '../components/logo.component';
import LoginPanel from '../components/login-panel.component';

class LoginPage extends React.Component {

  render() {

    return(
      <div className="sg-page__login">
        <div className="sg-vertical-align--center">
          <section>
            <Logo/>
            <LoginPanel/>
          </section>
        </div>
      </div>
    )
  }
}

export default LoginPage;

import React from 'react';

class LoginPanel extends React.Component {

  render() {

    return(
      <div className="sg-component__login-panel">
        <form method="POST" action="#">
          <label>
            <span>Username or e-mail</span>
            <input type="text" placeholder="Username or e-mail" required />
          </label>
          <label>
            <span>Password</span>
            <input type="password" placeholder="Password" required />
          </label>
          <div>
            <button>Login</button>
            <div>
              <a href="#">Have you forgotten your password</a>
              <span>Not a member yet?</span>
              <a href="#">Sign-up</a>
            </div>
          </div>
        </form>
      </div>
    );
  }
}

export default LoginPanel;

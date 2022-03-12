import React, { Component } from "react";

export class RedirectExternal extends Component {

  constructor(props) {
    super( props );
    //if the prefix is http or https, we add nothing
    let prefix = window.location.host.startsWith("http") ? "" : "http://";
    //using host here, as I'm redirecting to another location on the same host
    this.target = prefix + window.location.host + props.route.target;
  }
  componentDidMount() {
    window.location.replace(this.target);
  }
  render(){
    return (
      <div>
        <br />
        <span>Redirecting to {this.target}</span>
      </div>
    );
  }
}

export default RedirectExternal;
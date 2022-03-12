import React from 'react';

export default class Select extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            options: props.options || [],

        };

        console.log('this.state', this.state);

        this.handleInputChange = this.handleInputChange.bind(this);
    }

    handleInputChange(event) {
        this.setState({ [event.target.name]: event.target.value });
    }



    render() {

        return (
            // <select
            //     className="form-control"
            //     value={defaultValue}
            //     name="status_identifier"
            //     onChange={this.handleChange}
            // >
            //     {options}
            // </select>

            <div>

            </div>
        );
    }
}
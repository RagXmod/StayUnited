import React from 'react';

export default class Errors extends React.Component {

    renderErrors() {
        const { content } = this.props;
        const errorItems = Object.entries(content).map(([key, value], i) => {
			return (
                <li key={key}>
                    <span>{value} </span>
                </li>
			)
		})
        return (

            <ul>
                {errorItems}
            </ul>
        )
    }

    render() {
        return (
            <div>
                <h6>System Messages: </h6>
                {this.renderErrors()}
            </div>
        );
    }
}
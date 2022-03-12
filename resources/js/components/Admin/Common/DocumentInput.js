import React from 'react';

export default class DocumentInput extends React.Component {

    render() {

        return (

            <div className="form-group">
                <label htmlFor="${ this.props.index }_label">
                    {this.props.label || 'Input label'}
                </label>
                <input
                    className="form-control form-control-alt"
                    type={this.props.type || 'text'}
                    name={ `document-${ this.props.index }-document` } />
            </div>

        );
    }
}
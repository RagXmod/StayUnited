import React from 'react';

export default class AppMoreDetail extends React.Component {
    constructor(props) {
        super(props);

    }


    onClickDelBtn( index, evt  ) {
        this.props.onHandleDeleteClick( index );
    }

    onAppChange(index, identifier, evt) {
        this.props.onHandleDetailChange( index, evt.target.value, identifier );
    }


    render() {

        const { index, app } = this.props;
        let Idx = index;
        return (

            <div className="input-group">

                <input type="text"
                    className="form-control mr-1"
                    name={app.title}
                    placeholder={ app.title || 'Title Here' }
                    defaultValue={app.title}
                    onChange={this.onAppChange.bind(this, index, 'title')}
                />

                <div className="input-group-prepend">
                    <span className="input-group-text">Value</span>
                </div>
                <input  type="text"
                   name={app.identifier}
                   className="form-control"
                   placeholder={` ${app.value || 'Custom input value'} `}
                   defaultValue={app.value}
                   onChange={this.onAppChange.bind(this, index, 'value') }
                />

                <div  className="input-group-prepend">
                    <button  className="btn btn-outline-danger btn-sm" type="button" onClick={ this.onClickDelBtn.bind( this, index)}>
                        <i  className="fa fa-times"></i>
                    </button>
                </div>
            </div>
        );
    }
}
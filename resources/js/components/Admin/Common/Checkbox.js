import React from 'react';

export default class Checkbox extends React.Component {
    constructor(props) {
        super(props);
    }

    renderCheckboxItem() {
        const { collections, width } = this.props;
        return Object.entries(collections).map(([key, item], i) => {

            if ( !item.isChecked)
                item.isChecked = false;

			return (
                <li className="p-1" key={key}>
                    <input key={item.id}
                        onChange={this.props.handleCheckChildElement}
                        type="checkbox"
                        checked={item.isChecked}
                        value={item.id} />

                    { item.src || item.app_image_url || item.image_url ? (
                        <img  src={item.src || item.app_image_url || item.image_url } className="ml-2 mr-2"
                            alt={item.value || item.name || item.title}
                            width={ item.width || width ||  '20' }/>
                    ) : null }
                    <span className="mr-1"> {item.value || item.name || item.title}</span>
                </li>
			)
		});
    }
    render() {
        return (
            <div className={`form-row px-2 ${this.props.clsName || ''}`}>
                <ul className="list-unstyled">
                    { this.renderCheckboxItem() }
                </ul>
            </div>
        );
    }
}
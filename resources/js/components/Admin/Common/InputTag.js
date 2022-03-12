import React from 'react';

export default class InputTag extends React.Component {
    constructor(props) {
      super(props);

      // console.log('props.value', props.value);
      this.state = {
        items: (props.value || []).filter(n => n),
        focused: false,
        input: '',
        placeholder:  props.placeholder || 'Enter keyword',
        keywords:  props.keywords || 'keywords'
      };

      this.handleInputChange  = this.handleInputChange.bind(this);
      this.handleInputKeyDown = this.handleInputKeyDown.bind(this);
      this.handleRemoveItem   = this.handleRemoveItem.bind(this);
    }


    componentWillReceiveProps(props) {
      const { value } = this.props;
      // console.log('first call value: ', value);
      if ( value && props.value && Array.isArray(props.value) ) {
        this.setState({
          items: props.value
        })
      }
    }

    render() {

      const styles = {
        container: {
          border:       '1px solid #ddd',
          padding:      '5px',
          borderRadius: '0px',
        },
        items: {
          display:      'inline-block',
          padding:      '5px',
          border:       '1px solid #ebebef',
          fontFamily:   'Helvetica, sans-serif',
          borderRadius: '0px',
          marginRight:  '5px',
          cursor:       'pointer'
        },

        input: {
          width:      '100%',
          outline:    'none',
          border:     'none',
          fontSize:   '14px',
          fontFamily: 'Helvetica, sans-serif'
        }
      };
      return (
        <div>
          <ul style={styles.container}>
            {this.state.items.map((item, i) =>
              <li key={i} style={styles.items} onClick={this.handleRemoveItem(i)}>
                {item}
                <span><i className="fa fa-times-circle ml-1"></i></span>
              </li>
            )}
            <input
              name={this.keywords}
              placeholder={this.state.placeholder}
              style={styles.input}
              value={this.state.input}
              onChange={this.handleInputChange}
              onKeyDown={this.handleInputKeyDown} />
          </ul>
        </div>
      );
    }

    handleInputChange(evt) {
      this.setState({ input: evt.target.value });
    }

    handleInputKeyDown(evt) {
      if ( evt.keyCode === 13 ) {
        const {value} = evt.target;

        const uniqueNames = [...this.state.items, value].filter((val,id,array) => array.indexOf(val) == id);
        this.setState(state => ({
          items: uniqueNames,
          input: ''
        }));

        // update seo keywords
        this.props.onChange(uniqueNames);
      }

      if ( this.state.items.length && evt.keyCode === 8 && !this.state.input.length ) {
        this.setState(state => ({
          items: state.items.slice(0, state.items.length - 1)
        }));
      }
    }

    handleRemoveItem(index) {
      return () => {

        const filteredItem = this.state.items.filter((item, i) => i !== index);
        this.setState(state => ({
          items: filteredItem
        }));
        // update on remove item
        this.props.onChange(filteredItem);
      }
    }
}

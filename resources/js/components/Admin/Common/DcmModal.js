import React from 'react';
import { Button, Modal } from 'react-bootstrap';
import { isObject } from 'util';

import Errors from './Errors';


export default class DcmModal extends React.Component {

    render() {
      return (
        <Modal
          {...this.props}
          size={this.props.size}
          aria-labelledby="contained-modal-title-vcenter"
          centered
        >
          <Modal.Header closeButton>
            <Modal.Title id="contained-modal-title-vcenter">
              {this.props.title || 'Page Notifications'}
            </Modal.Title>
          </Modal.Header>
          <Modal.Body>
            { isObject( this.props.content ) ? (
              <Errors {...this.props} />
            ) : (
              <strong ><span dangerouslySetInnerHTML={{__html: this.props.content || 'Successfully Updated'}}>
              </span></strong>
            )}

          </Modal.Body>
          <Modal.Footer>
            <Button onClick={this.props.onHide}>Close</Button>
          </Modal.Footer>
        </Modal>
      );
    }
}
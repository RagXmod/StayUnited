import React from 'react';
export default class AppContent extends React.Component {
    constructor(props) {
        super(props);
    }


    onClickEditBtn( app, evt ) {
        window.location.replace( app.admin_detail_link);
    }

    onClickDelBtn( app  ) {
        this.props.onHandleDeleteClick( app );
    }


    render() {

        const { app } = this.props;

        const styles = {
            image: {
              width: '80px',
              height: '80px',
            },
            star_score: {
                width:  app.star_ratings_percentage + '%'
            }
        }
        return (
            <div className="col-lg-3 col-sm-6 col-xs-6">
                <a className="block block-rounded block-link-shadow">
                    <div className="block-content block-content-full d-flex align-items-center justify-content-between">
                        <div className="item  block-app-image">
                            <img style={styles.image} src={ app.app_image_url } width="100%" height="100%"
                                alt={ app.title + ` average rating ` + app.current_ratings}
                                title={ app.title + ` average rating ` + app.current_ratings}
                                onError={(e) => {
                                    e.target.src = app.no_image_url  // some replacement image
                                }} />
                        </div>
                        <div className="ml-3 text-right">
                            <p className="font-w600 mb-0 app-title">
                               { app.title_with_limit }
                            </p>
                            <div className="stars mb-3">
                                <span className="score" title={ app.title + ` average rating ` + app.current_ratings} style={styles.star_score}></span>
                                <span className="star">{ app.current_ratings }</span>
                            </div>
                            <button className="btn btn-outline-secondary btn-sm mr-1"
                                onClick={this.onClickEditBtn.bind(this, app) } >
                                <i className="fa fa-edit "></i>
                            </button>
                            <button className="btn btn-outline-danger btn-sm"
                                onClick={this.onClickDelBtn.bind(this, app) }>
                                <i className="fa fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </a>
            </div>
        );
    }
}
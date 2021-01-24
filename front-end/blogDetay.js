import React, { Component, Fragment } from "react";
import { Row, Card, CardBody, CardTitle } from "reactstrap";
import Breadcrumb from "../../containers/navs/Breadcrumb";
import { Separator, Colxx } from "../../components/common/CustomBootstrap";
import { injectIntl } from "react-intl";
import SingleLightbox from "../../components/pages/SingleLightbox";
import { blogData, blogCategories } from "../../data/blog"
import { NavLink } from "react-router-dom";
import { apiUrl } from "../../constants/defaultValues";
import Moment from 'moment';
import 'moment/locale/tr' //For Turkey
import SEO from "./seo";

class BlogDetail extends Component {
    constructor(props) {
        super(props);
        this.state = {
            blog: [],
            isLoading: null,
            blogLast: []
        };
    }

    componentDidMount()
    {
        var client = require('../../client');
        client.get('detail-blog/'+this.props.match.params.slug)
        .then(
          res => {
              this.setState({blog: res.data.data})
              client.get('list-blog-last')
              .then(
                res => {
                    this.setState({blogLast: res.data.data})
                   
                },
                err => {
          
                }
            )
              this.setState({isLoading: true})
          },
          err => {
    
          }
      )
    }

    render() {
        
        const {
           blog,
           blogLast
            } = this.state;
        return !this.state.isLoading ?  (
      <div className="loading" />
    ) : (
            <Fragment>
                 <SEO 
 title={blog.blogTitle} 
 description={blog.blogContent.replace(/(<([^>]+)>)/gi, "").slice(0,160)}
 image={apiUrl+"/blog/"+blog.imageHome}
 />
                <Row>
                    <Colxx xxs="12">
                        <Breadcrumb heading={blog.blogTitle} match={this.props.match} />
                        <Separator className="mb-5" />
                    </Colxx>
                </Row>

                <Row>
               
                    <Colxx xxs="12" md="12" xl="8" className="col-left">
                        <Card className="mb-4">
                            <SingleLightbox thumb={apiUrl+"/blog/"+blog.imageHome} large={apiUrl+"/blog/"+blog.imageHome} className="responsive border-0 card-img-top mb-3" />
                            
                            <CardBody>
                            <div class="ql-snow">
                <p class="ql-editor" dangerouslySetInnerHTML={{ __html:blog.blogContent }}>
                        
                        </p>
                </div>
                            </CardBody>
                            
                        </Card>
                    </Colxx>

                    <Colxx xxs="12" md="12" xl="4" className="col-left">
                        <Card className="mb-4">
                            <CardBody>
                                <p className="list-item-heading mb-4">Yazar <br></br>{blog.user.name} {blog.user.surname} </p> <a href={"/profil/@"+blog.user.username}>Profile Git</a>
                                <footer>
                                    <p className="text-muted text-small mb-0 font-weight-light">{Moment(blog.createdAt.date).format('LL HH:mm:ss')}</p>
                                </footer>
                            </CardBody>
                        </Card>
                        <Card className="mb-4">
                            <CardBody>
                                <CardTitle>
                                Diğer Blog Yazıları
                                </CardTitle>
                                {
                                    blogLast.map((blogItem, index) => {
                                        return (
                                            <div className={"d-flex flex-row " + (index === blogLast.length - 1 ? "" : "mb-3")} key={index}>
                                                <div>
                                                    <NavLink to="#">
                                                        <img src={apiUrl+"/blog/"+blogItem.imageHome} alt="img caption"
                                                            className="list-thumbnail border-0" />
                                                    </NavLink>
                                                </div>
                                                <div className="pl-3 pt-2 list-item-heading-container">
                                                    <NavLink to="#">
                                                        <ResponsiveEllipsis
                                                            className="list-item-heading"
                                                            text={blogItem.blogTitle}
                                                            maxLine='3'
                                                            trimRight={true}
                                                            basedOn='words'
                                                            component="h5" />
                                                    </NavLink>
                                                </div>
                                            </div>
                                        )
                                    })}
                            </CardBody>
                        </Card>
                        <Card className="mb-4">
                            <CardBody>
                                <CardTitle>
                                    Kariyerine Yön Ver
                                </CardTitle>
                                {
                                    blogCategories.map((categoryItem, index) => {
                                        return (
                                            <div class="d-flex flex-row align-items-center mb-3">
                                                <NavLink to={categoryItem.link}>
                                                    <i class={"large-icon initial-height " + categoryItem.icon}></i>
                                                </NavLink>
                                                <div class="pl-3 pt-2 pr-2 pb-2">
                                                    <NavLink to={categoryItem.link}>
                                                        <p class="list-item-heading mb-1">{categoryItem.title}</p>
                                                    </NavLink>
                                                </div>
                                            </div>
                                        )
                                    })}
                            </CardBody>
                        </Card>
                    </Colxx>
                </Row>
            </Fragment>
        );
    }
}
export default injectIntl(BlogDetail);

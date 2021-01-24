import React, { Component, Fragment } from 'react';
import { injectIntl } from 'react-intl';
import { Row, Card, CardBody,CardTitle } from 'reactstrap';
import { Colxx, Separator } from '../../components/common/CustomBootstrap';
import Breadcrumb from '../../containers/navs/Breadcrumb';
import GlideComponent from "../../components/carousel/GlideComponent";
import Moment from 'moment';
import 'moment/locale/tr' //For Turkey
import SEO from "./seo";
import ImageOverlayCard from "../../containers/ui/ImageOverlayCard";
import { apiUrl } from "../../constants/defaultValues";

const BasicCarouselItemBlog = ({ user, blogTitle, blogContent, slug,imageHome }) => {
  return (
    <a href={"blog/"+slug}>
    <div className="glide-item" >
      <Card className="flex-row" style={{height:186}}>
        <div className="w-50 position-relative">
          <img className="card-img-left" src={apiUrl+"/blog/"+imageHome} alt={blogTitle} />
        </div>
        <div className="w-50">
          <CardBody >
            <h6 className="mb-4">{blogTitle}</h6>
            <footer>
            <ResponsiveEllipsis 
                                                    className="listing-desc text-muted"
                                                    text={blogContent.slice(0,500)}
                                                    maxLine='3'
                                                    trimRight={true}
                                                    basedOn='words'
                                                    component="p" />
            </footer>
          </CardBody>
        </div>
      </Card>
    </div>
    </a>
  );
};
const BasicCarouselItem = ({ slug, questionTitle, createdAt, user }) => {
  return (
  <a href={`/soru/${slug}`}>
    <div  className="glide-item">
      <Card className="flex-row">
       
        <div className="w-100">
          <CardBody >
            <h6 className="mb-4">{questionTitle}</h6>
            <footer>
              <p className="text-muted text-small mb-0 font-weight-light">
               <a href={"/profil/@"+user.username}> @{user.username}</a>
                <br></br><br></br>
              {Moment(createdAt.date).format('LL HH:mm:ss')}
    <br></br>
{
Moment(createdAt.date).startOf('hour').fromNow()
}
              </p>
            </footer>
          </CardBody>
        </div>
      </Card>
    </div>
    </a>
  );
};


class Anasayfa extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoading: null,
      soru: [],
      test: [],
      blogLast: []

    };
    

  }

  
  componentDidMount() {
    var client = require('../../client');
    client.get("list-question")
    .then(
      res => {
          if(res.data.data)
          {
            this.setState({soru: res.data.data})
          } 
         
          this.setState({isLoading: true});
      },
      err => {
        
        this.setState({isLoading: true});
        
          }
  )
  client.get('list-blog-last')
              .then(
                res => {
                  if(res.data.data)
                  {
                    this.setState({blogLast: res.data.data})
                  }
                  
                   
                },
                err => {
          
                }
            )
  }
   
  render() {
    const { soru ,test,blogLast } = this.state;

    return !this.state.isLoading ? (
         <div className="loading" />
    ) : (
      
      <Fragment>
        <SEO 
        title="Yönetim Bilişim Sistemleri Kariyer Uygulaması"
         description="Yönetim Bilişim Sistemleri (YBS) Kariyer Uygulaması, YbsKariyer.com" 
         />
        <Row>
          <Colxx xxs="12">
            <Breadcrumb heading="Yönetim Bilişim Sistemleri Kariyer Uygulaması" match={this.props.match} />
            <Separator className="mb-1" />
          </Colxx>
        </Row>
        <Row>
        {anasayfaBlogData && anasayfaBlogData.length > 1 && anasayfaBlogData.map(item => {
                return (
                  
                    <ImageOverlayCard {...item}>
                      </ImageOverlayCard>
                   
                    
                 
                );
              })}
        </Row>
        <Row>
          
        
        {soru.length ?  
        <Colxx  xxs="12" xs="12" lg="6" className="pl-0 pr-0 mb-5">
        <Colxx xxs="12">
          <div className="text-center pt-4">
              <h5 className="list-item-heading pt-2">Son Sorulan Sorular</h5>
          </div>
        </Colxx>
            <GlideComponent settings={
              {
                gap: 5,
                perView: 2,
                type: "carousel",
                breakpoints: {
                  600: { perView: 1 },
                  600: { perView: 1 }
                }
              }
            }>
              
              {soru && soru.map(item => {
                return (
                  <div key={item.id}>
                    <BasicCarouselItem {...item} />
                  </div>
                );
              })}
            </GlideComponent>
          </Colxx>
     : null}
     
     {blogLast && blogLast.length > 0 ? 
          <Colxx  xxs="12" xs="12" lg="6" className="pl-0 pr-0 mb-5">
          <Colxx xxs="12">
          <div className="text-center pt-4">
              <h5 className="list-item-heading pt-2">Son Yazılan Bloglar</h5>
          </div>
          </Colxx>
            <GlideComponent settings={
              {
                gap: 5,
                perView: 1,
                type: "carousel",
                breakpoints: {
                  600: { perView: 1 },
                  600: { perView: 1 }
                }
              }
            }>
              {blogLast && blogLast.length > 0 && blogLast.map(item => {
                return (
                  <div key={item.id}>
                    <BasicCarouselItemBlog {...item} />
                  </div>
                );
              })}
            </GlideComponent>
          </Colxx>
           : null}
       
        </Row>
        
      

          
          
       
      </Fragment>
      
    );
  }
}
export default injectIntl(Anasayfa);

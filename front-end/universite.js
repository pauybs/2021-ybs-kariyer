import React, { Component, Fragment } from "react";
import { injectIntl } from 'react-intl';
import { Row ,  Card,
  CardBody,
  Nav,
  NavItem,
  UncontrolledDropdown,
  DropdownToggle,
  DropdownItem,
  DropdownMenu,
  TabContent,
  TabPane,
  Badge,
  Button,
  CardTitle,} from "reactstrap";
import { Colxx, Separator } from "../../components/common/CustomBootstrap";
import Breadcrumb from "../../containers/navs/Breadcrumb";
import Moment from 'moment';
import 'moment/locale/tr' //For Turkey
import SEO from "./seo";
import { NavLink } from "react-router-dom";


class Universite extends Component {
  constructor(props) {
    super(props);
    this.state = {araba: [],
      posts:[],
      manager: []
    };
    this.friendsData = whotoFollowData.slice();
    this.followData = this.state.manager.slice(0,5);

  }
 
  componentDidMount() {
    this.dataRenderUniversite();
    var client = require('../../client');
    client.get('list-manager-university-user/'+this.props.match.params.slug)
    .then(
      res => {
          this.setState({manager: res.data.data})
      },
      err => {

      }
  )
   
   
  }

  componentWillUnmount() {
    if (this.scrolls$) this.scrolls$.unsubscribe();
  }
  
  dataRenderUniversite()
  {
    var client = require('../../client');
    client.get("get-university/"+this.props.match.params.slug)
     .then(res => {
       return res.data;
     })
    
     .then(data => {
       console.log(data.pageCount);
       this.setState({
         items: data.data
       });
       client.get("list-university-post/"+this.props.match.params.slug)
       .then(res => {
         return res.data;
       })
      
       .then(data => {
      
         this.setState({
           posts: data.data,
           isLoading: true
         });
       })
       .catch(err=> {
         this.setState({
           
           posts: [],
           isLoading: true
         });
       });
     })
     .catch(err=> {
       this.setState({
         
         items: [],
         isLoading: null
       });
       return this.props.history.push('/universiteler');
     });
  }
  render() {

    Moment.locale('tr'); //For Turkey

const  divRef = React.createRef();

    const {messages} = this.props.intl;
    const {
    items,
    posts
    } = this.state;
    return !this.state.isLoading ? (
      <div className="loading" />
    ) : (
      <Fragment>
          <SEO 
 title={"YBS "+items.universityName} 
 description={"Yönetim Bilişim Sistemleri "+items.universityName+" paylaşımları. YbsKariyer.com"}
 />
 
        <Row >
          
          <Colxx xxs="12"  className="mb-5">
            <Breadcrumb heading={items.universityName} match={this.props.match}/>
            <br></br>
            <NavLink to={this.props.match.url+'/ogrenci-basvuru'}>
            <Button  outline color="secondary" className="mb-2 m-1">
                  Öğrenci Bilgi Sistemine Başvur
                </Button>
              </NavLink>
              <NavLink to={this.props.match.url+'/mezun-basvuru'}>
                <Button outline color="secondary" className="mb-2 m-1">
                  Mezun Bilgi Sistemine Başvur
                </Button>
                </NavLink>
                <NavLink to={this.props.match.url+'/mezunlar'}>
                <Button outline color="secondary" className="mb-2 m-1">
                  Mezunlar
                </Button>
                </NavLink>
                <Button outline color="secondary" className="mb-2 m-1">
                  Öğrencilere Sor
                </Button>
        
            <Separator className="mb-5" />
            <br></br>
            
                      </Colxx>
        </Row>
        
        <Row>
                 
        
                  <Colxx xxs="12" lg="5" xl="4"  className="col-left">

                    <SingleLightbox thumb={items.universityLogo} large={items.universityLogo} className="img-thumbnail card-img social-profile-img" />

                    <Card className="mb-4">
                      <CardBody>
                        <div className="text-center pt-4">
                <p className="list-item-heading pt-2">{items.universityName}</p>
                        </div>
                        <p className="mb-3">
                        
            <p>{items.createdAt.date}</p>
            {Moment(items.createdAt.date).format('LL HH:mm:ss')}
            <p> {
              Moment(items.createdAt.date).startOf('hour').fromNow()
            }</p>
            {items.universityContent}
                        </p>
                        <p className="text-muted text-small mb-2">Şehir</p>
                        <p className="mb-3">{items.universityCity.cityName}</p>
                    
                        <p className="text-muted text-small mb-2">Sosyal Medya</p>
                        <div className="social-icons">
                          <ul className="list-unstyled list-inline">
                            <li className="list-inline-item">
                              <NavLink to="#"><i className="simple-icon-social-facebook"></i></NavLink>
                            </li>
                            <li className="list-inline-item">
                              <NavLink to="#"><i className="simple-icon-social-twitter"></i></NavLink>
                            </li>
                            <li className="list-inline-item">
                              <NavLink to="#"><i className="simple-icon-social-instagram"></i></NavLink>
                            </li>
                          </ul>
                        </div>
                      </CardBody>
                    </Card>

                 

                    <Card className="mb-4">
                      <CardBody>
                        <CardTitle>
                          Üniversite Temsilcileri
                        </CardTitle>
                        <div className="remove-last-border remove-last-margin remove-last-padding">
                          { this.state.manager ? 
                            this.state.manager.map((itemData) => {
                              return <UserFollow data={itemData} key={itemData.key} />
                            })  : "Üniversite Temsilcisi Bulunmuyor, Temsilci Başvuru İçin Tıklayın"
                          }
                        </div>
                      </CardBody>
                    </Card>

                   
                   
                  </Colxx>
             
                  <Colxx xxs="12" lg="7" xl="8" className="col-right">
                    {
                      posts.map((itemData) => {
                        return <Post data={itemData} key={itemData.key} className="mb-4" />
                      })
                    }
                  </Colxx>
                </Row>
      </Fragment>
    );
  }
}
export default injectIntl(Universite);
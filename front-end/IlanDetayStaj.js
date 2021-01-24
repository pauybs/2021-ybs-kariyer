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
import SEO from "./seo";

import Moment from 'moment';
import 'moment/locale/tr' //For Turkey

import { NavLink } from "react-router-dom";
import whotoFollowData from "../../data/follow";
class Universite extends Component {
  constructor(props) {
    super(props);
    this.state = {araba: [],
      posts:[],
      manager: [],
      internAd: [],
      isLoading: null
    };
    this.friendsData = whotoFollowData.slice();
    this.followData = this.state.manager.slice(0,5);

  }
 
  componentDidMount() {
    
    var client = require('../../client');
    client.get('get-intern-ad/'+this.props.match.params.slug)
    .then(
      res => {
          this.setState({internAd: res.data.data})
          this.setState({isLoading: true})
      },
      err => {

      }
  )
   
   
  }

  componentWillUnmount() {
    if (this.scrolls$) this.scrolls$.unsubscribe();
  }
  
 
  render() {

    Moment.locale('tr'); //For Turkey

const  divRef = React.createRef();
    const {messages} = this.props.intl;
    const {
    items,
    posts,
    internAd
    } = this.state;
    return !this.state.isLoading ?  (
      <div className="loading" />
    ) : (
      <Fragment>
               <SEO 
 title={internAd.internTitle+" - Staj İlanı"} 
 description={internAd.internContent.replace(/(<([^>]+)>)/gi, "").slice(0,160)}
 />
        <Row >
          
          <Colxx xxs="12"  className="mb-5">
            <Breadcrumb heading={internAd.internTitle} match={this.props.match}/>
            <Separator className="mb-5" />
            <br></br>
            
                      </Colxx>
        </Row>
        
        <Row>
                 
        
                  <Colxx xxs="12" lg="5" xl="4"  className="col-left">

                    <Card className="mb-4">
                      <CardBody>
                        <div className="text-center pt-4">
                <p className="list-item-heading pt-2">{internAd.internTitle}</p>
                        </div>
                        <p className="mb-3">
            {Moment(internAd.createdAt.date).format('LL HH:mm:ss')}
            <p> {
              Moment(internAd.createdAt.date).startOf('hour').fromNow()
            }</p>
                        </p>
                        {internAd.internType === false ? 
                        <div>
                          <p className="text-muted text-small mb-2">Şirket</p>
                        <p className="mb-3">{internAd.internCompany}</p>
                        </div>  
                        : null
                        }

                        {internAd.internType === false ? 
                        <div>
                          <p className="text-muted text-small mb-2">Şirket Sektörü</p>
                        <p className="mb-3">{internAd.workplaceSector.sectorName}</p>
                        </div>  
                        : null
                        }
                        <p className="text-muted text-small mb-2">Şehir</p>
                        <p className="mb-3">{internAd.internCity.cityName}</p>
                        <p className="text-muted text-small mb-2">Pozisyon</p>
                        <p className="mb-3">{internAd.internPosition.positionName}</p>

                        <p className="text-muted text-small mb-2">İlan Görüntülenme</p>
                        <p className="mb-3">{internAd.internViews}</p>
                    
                      
                      </CardBody>
                    </Card>

                   

                    <Card className="mb-4">
                      <CardBody>
                        <CardTitle>
                          İlan Sahibi
                        </CardTitle>
                        <div className="remove-last-border remove-last-margin remove-last-padding">
                        <div className="d-flex flex-row mb-3 pb-3 border-bottom justify-content-between align-items-center">
                
                <div className="pl-3 flex-fill">
                    <NavLink to={"/profil/@"+internAd.user.username}>
                        <p className="font-weight-medium mb-0">{internAd.user.name} {internAd.user.surname}</p>
                        <p className="text-muted mb-0 text-small">{internAd.user.username}</p>
                    </NavLink>
                </div>
                <div>
                    <NavLink className="btn btn-outline-primary btn-xs" to={"/profil/@"+internAd.user.username}>Profil</NavLink>
                </div>
            </div>
                        </div>
                      </CardBody>
                    </Card>

                   
                  </Colxx>
             
                  <Colxx xxs="12" lg="7" xl="8" className="col-right">
                  <Card >
                <CardBody>
                    
                    <p dangerouslySetInnerHTML={{ __html:internAd.internContent }}>
                        
                    </p>

                </CardBody>
            </Card>
                  </Colxx>
                </Row>
      </Fragment>
    );
  }
}
export default injectIntl(Universite);
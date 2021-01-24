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
import { NavLink } from "react-router-dom";
import SEO from "./seo";
import whotoFollowData from "../../data/follow";
class Universite extends Component {
  constructor(props) {
    super(props);
    this.state = {araba: [],
      posts:[],
      manager: [],
      jobAd: [],
      isLoading: null
    };
    this.friendsData = whotoFollowData.slice();
    this.followData = this.state.manager.slice(0,5);

  }
 
  componentDidMount() {
    
    var client = require('../../client');
    client.get('get-job-ad/'+this.props.match.params.slug)
    .then(
      res => {
          this.setState({jobAd: res.data.data})
          this.setState({isLoading: true})
      },
      err => {
          this.props.history.push('/is-ilanlari');
      }
  )
   
   
  }

  componentWillUnmount() {
    if (this.scrolls$) this.scrolls$.unsubscribe();
  }
  
 
  render() {

    Moment.locale('tr'); //For Turkey

const  divRef = React.createRef();
    console.log(this.state.items);
let slug = this.props.match.params.slug;
console.log('slug= ', slug);
    const {messages} = this.props.intl;
    const {
    items,
    posts,
    jobAd
    } = this.state;
    return !this.state.isLoading ?  (
      <div className="loading" />
    ) : (
      <Fragment>
         <SEO 
 title={jobAd.jobTitle+" - İş İlanı"} 
 description={jobAd.jobContent.replace(/(<([^>]+)>)/gi, "").slice(0,160)}
 />
        <Row >
          
          <Colxx xxs="12"  className="mb-5">
            <Breadcrumb heading={jobAd.jobTitle} match={this.props.match}/>
            <Separator className="mb-5" />
            <br></br>
            
                      </Colxx>
        </Row>
        
        <Row>
                 
        
                  <Colxx xxs="12" lg="5" xl="4"  className="col-left">

                    <Card className="mb-4">
                      <CardBody>
                        <div className="text-center pt-4">
                <p className="list-item-heading pt-2">{jobAd.jobTitle}</p>
                        </div>
                        <p className="mb-3">
            {Moment(jobAd.createdAt.date).format('LL HH:mm:ss')}
            <p> {
              Moment(jobAd.createdAt.date).startOf('hour').fromNow()
            }</p>
                        </p>
                        {jobAd.jobType === false ? 
                        <div>
                          <p className="text-muted text-small mb-2">Şirket</p>
                        <p className="mb-3">{jobAd.jobCompany}</p>
                        </div>  
                        : null
                        }

                        {jobAd.jobType === false ? 
                        <div>
                          <p className="text-muted text-small mb-2">Şirket Sektörü</p>
                        <p className="mb-3">{jobAd.workplaceSector.sectorName}</p>
                        </div>  
                        : null
                        }
                        <p className="text-muted text-small mb-2">Şehir</p>
                        <p className="mb-3">{jobAd.jobCity.cityName}</p>
                        <p className="text-muted text-small mb-2">Pozisyon</p>
                        <p className="mb-3">{jobAd.jobPosition.positionName}</p>

                        <p className="text-muted text-small mb-2">İlan Görüntülenme</p>
                        <p className="mb-3">{jobAd.jobViews}</p>
                    
                      
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
                    <NavLink to={"/profil/@"+jobAd.user.username}>
                        <p className="font-weight-medium mb-0">{jobAd.user.name} {jobAd.user.surname}</p>
                        <p className="text-muted mb-0 text-small">{jobAd.user.username}</p>
                    </NavLink>
                </div>
                <div>
                    <NavLink className="btn btn-outline-primary btn-xs" to={"/profil/@"+jobAd.user.username}>Profil</NavLink>
                </div>
            </div>
                        </div>
                      </CardBody>
                    </Card>

                 
                  </Colxx>
             
                  <Colxx xxs="12" lg="7" xl="8" className="col-right">
                  <Card >
                <CardBody>
                    
                    <p dangerouslySetInnerHTML={{ __html:jobAd.jobContent }}>
                        
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
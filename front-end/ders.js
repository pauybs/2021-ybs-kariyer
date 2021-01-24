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
  Button,Jumbotron,
  CardTitle,} from "reactstrap";

import { Colxx, Separator } from "../../components/common/CustomBootstrap";
import Breadcrumb from "../../containers/navs/Breadcrumb";
import Moment from 'moment';
import 'moment/locale/tr' //For Turkey
import SEO from "./seo";
import whotoFollowData from "../../data/follow";
class Ders extends Component {
  constructor(props) {
    super(props);
    this.state = {araba: [],
     
    
    };
    this.friendsData = whotoFollowData.slice();
    this.followData = whotoFollowData.slice(0,5);

  }
 
  componentDidMount() {
    this.dataRenderUniversite();

   
   
  }

  componentWillUnmount() {
    if (this.scrolls$) this.scrolls$.unsubscribe();
  }
  
  dataRenderUniversite()
  {
    var client = require('../../client');
    client.get("get-lesson/"+this.props.match.params.slug)
     .then(res => {
       return res.data;
     })
    
     .then(data => {
       console.log(data.pageCount);
       this.setState({
         items: data.data,
         isLoading: true
       });
     })
     .catch(err=> {
       this.setState({
         
         items: [],
         isLoading: true
       });
       return this.props.history.push('/dersler');
     });
  }
  render() {

    Moment.locale('tr'); //For Turkey

const  divRef = React.createRef();
    console.log(this.state.items);
let slug = this.props.match.params.slug;
let url = this.props.match.url;
console.log('slug= ', slug);
let token = localStorage.getItem('token');
    const {messages} = this.props.intl;
    const {
    items
    } = this.state;
    return !this.state.isLoading ? (
      <div className="loading" />
    ) : (
      <Fragment>
         <SEO 
 title={items.lessonName+ " Dersi"} 
 description={items.lessonContent.replace(/(<([^>]+)>)/gi, "").slice(0,160)}
 />
        <Row >
          
          <Colxx xxs="12"  className="mb-5">
            <Breadcrumb heading={items.lessonName} match={this.props.match}/>
           
            <Separator className="mb-5" />
            <br></br>
            
                      </Colxx>
        </Row>
        
        <Row>
        <Colxx xxs="1" className="">
          </Colxx>
        <Colxx xxs="10" className="">
            <Card>
                <Jumbotron >
                  <div  dangerouslySetInnerHTML={{ __html:items.lessonContent }}>

                  </div>
                  </Jumbotron>
                 
                  </Card>
                  </Colxx> 
                  <Colxx xxs="1" className="">
          </Colxx>
        </Row>
      </Fragment>
    );
  }
}
export default injectIntl(Ders);
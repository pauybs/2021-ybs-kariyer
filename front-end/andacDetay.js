import React, { Component, Fragment } from 'react';
import { Route } from 'react-router-dom';
import { injectIntl } from 'react-intl';
import { Row,Card,CardBody,Jumbotron,Button, FormGroup, Label ,CustomInput } from 'reactstrap';
import { Colxx, Separator } from '../../components/common/CustomBootstrap';
import Breadcrumb from '../../containers/navs/Breadcrumb';
import SEO from "./seo";

class SoruSor extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoading: null,
      loading: "",
      content: "",
      user: [],
      universityId: ""
    };
  }

  handleChangeQuillStandart = (content) => {
    this.setState({ content });
  }
  componentDidMount()
  {
    var client = require('../../client');
    client.get('detail-andac/'+this.props.match.params.id)
    .then(
      res =>{
            this.setState({content: res.data.data.content})
            this.setState({user: res.data.data.writerUser})
            if(this.props.match.params.username != res.data.data.ownerUser.username)
            {
              this.props.history.push('/');
            }
            this.setState({isLoading: true})
      },
      err=>{
            this.props.history.push('/');
      }
    )
  }


  render() {
    const { content,loading,user } = this.state;
    const initialValues = {content};
    return !this.state.isLoading ? (
      <div className="loading" />
    ) : (
      <Fragment>
         <SEO 
 title={"@"+this.props.match.params.username+" Andaç"} 
 description={content.replace(/(<([^>]+)>)/gi, "").slice(0,160)}
 />
        <Row>
         
          <Colxx xxs="12">
          
            <Breadcrumb heading="Andaç Mektubu" match={this.props.match} />
            
            <br></br>
          
            
            <br></br>
            <Separator className="mb-1" />
          </Colxx>
        </Row>
        <Row>

        <Colxx xxs="12 p-2" className="mb-4">
            <Card>
              <CardBody>
                {user.name} {user.surname} Kişisinin @{this.props.match.params.username} kullanıcısına yazmış olduğu andaç.
                <Jumbotron>
                
               
                      <Label>
                        Andaç Mektubunuz
                      </Label>
                
                    
                    <div class="ql-snow">
                <p class="ql-editor" dangerouslySetInnerHTML={{ __html:content }}>
                        
                        </p>
                </div>
                  
        
                  </Jumbotron>
                  </CardBody>
                  </Card>
                  </Colxx>
        </Row>
      </Fragment>
    );
  }
}
export default injectIntl(SoruSor);

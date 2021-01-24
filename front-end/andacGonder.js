import React, { Component, Fragment } from 'react';
import { injectIntl } from 'react-intl';
import { Row,Card,CardBody,Jumbotron,Button, FormGroup, Label ,CustomInput } from 'reactstrap';
import { Colxx, Separator } from '../../components/common/CustomBootstrap';
import Breadcrumb from '../../containers/navs/Breadcrumb';
import { Formik, Form, Field } from "formik";
import { NotificationManager } from "../../components/common/react-notifications";
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
    client.get('get-user-andac?username='+this.props.match.params.username+"&university="+this.props.match.params.slug)
    .then(
      res =>{
            this.setState({universityId: res.data.data.universityId})
            this.setState({user: res.data.data.user})
            this.setState({isLoading: true})
      },
      err=>{
            this.props.history.push('/');
      }
    )
  }

  onAndacCreate = (values) => {
    if (this.state.content !== "") {
      this.setState({loading : 1}); 
   
     var bodyFormData = new FormData();
     bodyFormData.append('universityId', this.state.universityId);
     bodyFormData.append('content', this.state.content);
    const options = {
       
      headers: {
      'Content-Type': 'application/json',
      }
      }
      var client = require('../../client');

      client.post("add-andac/"+this.props.match.params.username, bodyFormData, options)
        .then(
            res => {
              
              this.setState({loading : null});
              console.log(res.data); 
              NotificationManager.success(
                'Andaç başarılı şekilde yazıldı!',
                "Başarılı",
                3000,
                null,
                null,
                ''
              );
              // this.props.history.push('/admin/universite');
            },
            err => {
              this.setState({loading : null}); 
              if(err.response.data.message)
              {
                err.response.data.message.map((message, i) => {     
                  NotificationManager.error(
                    message,
                    "Hata",
                    3000,
                    null,
                    null,
                    ''
                  );
               })
              }
            
                if(err.response === undefined){
                  NotificationManager.error(
                    'Sunucuya bağlanılırken hata oluştu !',
                    "Sunucu Hatası",
                    3000,
                    null,
                    null,
                    ''
                  );
                }
            }
        )
    this.setState({content: ""});
    }
    
}






  render() {
    const { content,loading,user } = this.state;
    const initialValues = {content};
    return !this.state.isLoading ? (
      <div className="loading" />
    ) : (
      <Fragment>
         <SEO 
        title="Andaç Gönder" 
        description="YbsKariyer.com ile mezun olduğun arkdaşlarına andaç gönderebilirsin."
        />
 
        <Row>
         
          <Colxx xxs="12">
          
            <Breadcrumb heading="Andaç Yaz" match={this.props.match} />
            
            <br></br>
          
            <br></br>
            <Separator className="mb-1" />
          </Colxx>
        </Row>
        <Row>

        <Colxx xxs="12 p-2" className="mb-4">
            <Card>
              <CardBody>
                {user.name} {user.surname} Kişisine Andaç Yazıyorsunuz
                <Jumbotron>
                
                <Formik
                initialValues={initialValues}
                onSubmit={this.onAndacCreate}>
                {({ errors, touched }) => (
                  <Form className="av-tooltip tooltip-label-bottom">
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        Andaç Mektubunuz
                      </Label>
                    <ReactQuill
                  theme="snow"
                  value={this.state.content}
                  onChange={this.handleChangeQuillStandart}
                  modules={quillModules}
                  formats={quillFormats}
                  />
                    </FormGroup>
                    
                    
                    <div className="d-flex justify-content-between align-items-center">
                     
                      <Button
                        color="primary"
                        className={`btn-shadow btn-multiple-state ${this.state.loading ? "show-spinner" : ""}`}
                        size="lg"
                      >
                        <span className="spinner d-inline-block">
                          <span className="bounce1" />
                          <span className="bounce2" />
                          <span className="bounce3" />
                        </span>
                        <span className="label">Andaç Yaz</span>
                      </Button>
                    </div>


                  </Form>
                )}
              </Formik>
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

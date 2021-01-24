import React, { Component, Fragment } from 'react';
import { injectIntl } from 'react-intl';
import { Row,Card,CardBody,CardText,CardTitle,Jumbotron,Button, FormGroup, Label  } from 'reactstrap';
import { Colxx, Separator } from '../../components/common/CustomBootstrap';
import Breadcrumb from '../../containers/navs/Breadcrumb';
import { Formik, Form, Field } from "formik";
import { NotificationManager } from "../../components/common/react-notifications";
import { NavLink } from "react-router-dom";
import Moment from 'moment';
import 'moment/locale/tr' //For Turkey
import SEO from "./seo";
const CevapItem = ({ id, answer, createdAt, user }) => {
  return (
    <Colxx xxs="12 p-1">
  
    <Card>
          <CardBody>
          
            
              
                    <div 
                      className="d-flex flex-row mb-3 pb-3 border-bottom">
                      <NavLink to={'/profil/@'+user.username}>
                        {user.name} {user.surname}
                        <br></br>
                        @{user.username}
                      </NavLink>
    
                      <div className="pl-3 pr-2">
                        
                          <p className="font-weight-medium mb-0">Tarih</p>
                          <p className="text-muted mb-0 text-small">
                          {Moment(createdAt.date).format('LL HH:mm:ss')}
        <br></br>
    {
    Moment(createdAt.date).startOf('time').fromNow()
    }
                          </p>
                          
                      
                      </div>
                      {localStorage.getItem('user') && JSON.parse(localStorage.getItem('user')).username == user.username ?
<Colxx xxs="2">
  İşlemler : <br></br>
  <NavLink to={'/cevap-guncelle/'+id}>
  <Button>
     Güncelle
   </Button>
  </NavLink>
   
</Colxx>
: null }
                    </div>
              
                <p> {answer}</p>
            
          </CardBody>
        </Card>
        
         </Colxx>
  );
};
class SoruDetay extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoading: null,
      loading: null,
      soru: [],
      answer: "",
      cevaplar: []
    };
    this.questionAnswer = this.questionAnswer.bind(this);
  }
  componentDidMount()
  {
    
    var client = require('../../client');
    client.get("get-question/"+this.props.match.params.slug)
    .then(
      res => {
          
          
          this.setState({soru: res.data.data});
          client.get("list-question-answer/"+this.props.match.params.slug)
          .then(
            res => {
                
                this.setState({cevaplar: res.data.data});
                this.setState({isLoading: true});
            },
            err => {
              this.setState({isLoading: true});
               
                }
        )
          

      },
      err => {
          this.props.history.push('/sorular');
          NotificationManager.warning(
            'Böyle bir soru bulunamadı !',
            "Hata",
            3000,
            null,
            null,
            ''
          );
          }
  )

  } 


 
  questionAnswer = (values) => {
    if(this.state.soru.id != null)
    {
      this.setState({loading : 1}); 
      var client = require('../../client');
      const options = {
         
        headers: {
        'Content-Type': 'application/json',
        }
        }
      var bodyFormData = new FormData();
       bodyFormData.append('answer', values.answer);
       bodyFormData.append('questionId', this.state.soru.id);
      client.post('add-question-answer',bodyFormData, options)
      .then(
        res => {
          setTimeout(function(){
            window.location.reload(1);
         }, 3000);
         NotificationManager.success(
          "Cevabınız başarılı şekilde yapıldı.",
          "Başarılı",
          3000,
          null,
          null,
          ''
        );
        
        this.setState({loading : null}); 
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

    values.answer = "";

    }

  };

  validateAnswer = (value) => {
    let error;
    if (!value) {
      error = "Lütfen cevap giriniz!";
    } else if (value.length < 2) {
      error = "Cevap 2 karakterden az olamaz !";
    }
    return error;
  }

  render() {
    const { soru,isLoading, studentApply, answer,cevaplar } = this.state;
    function getText(html){
      var divContainer= document.createElement("div");
      divContainer.innerHTML = html;
      return divContainer.textContent || divContainer.innerText || "";
  }
  
  
  
  console.log(getText(soru.questionContent))
    const initialValues = { answer };
    return !this.state.isLoading ? (
      <div className="loading" />
    ) : (
      <Fragment>
        <SEO 
 title={soru.questionTitle} 
 description={soru.questionContent.replace(/(<([^>]+)>)/gi, "").slice(0,160)}
 />
        <Row>
        
          <Colxx xxs="12">
        
            <Breadcrumb heading={'Soru : '+soru.questionTitle} match={this.props.match} />
            <Row>

                
<Colxx xxs="3">
    Soru Sahibi : <a href={'/profil/@'+soru.user.username} target="_blank"> <p>{soru.user.name} {soru.user.surname} <br></br> @{soru.user.username}</p> </a>  
</Colxx>
<Colxx xxs="2">
  Soru Tarihi : <br></br>
    {Moment(soru.createdAt.date).format('LL HH:mm:ss')}
    <br></br>
{
Moment(soru.createdAt.date).startOf('hour').fromNow()
}
</Colxx>
{localStorage.getItem('user') && JSON.parse(localStorage.getItem('user')).username == soru.user.username ?
<Colxx xxs="2">
  İşlemler : <br></br>
  <NavLink to={'/soru-guncelle/'+soru.slug}>
  <Button>
     Güncelle
   </Button>
  </NavLink>
   
</Colxx>
: null }
</Row>

            <Separator className="mb-1" />
          </Colxx>
        </Row>
        <Row>
        
          <Colxx xxs="12 p-2" className="mb-4">
            <Card>
              <CardBody>
               
            
              
              <br></br>
                  
                  <h1>
                  {soru.questionTitle}
                  </h1>
                  <hr className="my-1" />
                  <div dangerouslySetInnerHTML={{ __html:soru.questionContent }} >
                      </div>
              </CardBody>
            </Card>
          </Colxx>

          {!localStorage.getItem('user') ? 
            
            <Colxx xxs="12 p-2" className="mb-4">
            <Card>
              <CardBody>
              
                  <h3>
                  Cevap verebilmek için giriş yapmalısın !
                  </h3>
                  <div className="user d-inline-block">
          <Button className="p-2 m-2" >
            <NavLink to="/user/register" style={{color:'white'}}>
             Hesap Oluştur
            </NavLink>
          </Button>
          /
          <Button className="p-2 m-2" >
          <NavLink to="/user/login" style={{color:'white'}}>
             Giriş Yap
            </NavLink>
          </Button>
          </div>
                  </CardBody>
                  </Card>
                  </Colxx>
        
          : null  
        }
          {localStorage.getItem('user') ?
          
          <Colxx xxs="12 p-2" className="mb-4">
            <Card>
              <CardBody>
              
                  <h1>
                  Soruyu Cevapla
                  </h1>
                 
                  <Formik
                initialValues={initialValues}
                onSubmit={this.questionAnswer}>
                {({ errors, touched }) => (
                  <Form className="av-tooltip tooltip-label-bottom">
                    <FormGroup className="form-group has-float-label">
                      <Label>
                        Cevabınız
                      </Label>
                      <Field
                        className="form-control"
                        
                        component="textarea"
                       
                        name="answer"
                        validate={this.validateAnswer}
                      />
                      {errors.answer && touched.answer && (
                        <div className="invalid-feedback d-block">
                          {errors.answer}
                        </div>
                      )}
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
                        <span className="label">Soruyu Cevapla</span>
                      </Button>
                    </div>


                  </Form>
                )}
              </Formik>
              </CardBody>
            </Card>
          </Colxx>
          :null
          }
        <Colxx xxs="12">
        <Breadcrumb heading="Cevaplar" match={this.props.match} />
        <hr className="my-1" />
        </Colxx>
      
        </Row>
     
        {cevaplar && cevaplar.map(item => {
                return (
                  <div key={item.id}>
                    <CevapItem {...item} />
                  </div>
                );
              })}
        
      </Fragment>
    );
  }
}
export default injectIntl(SoruDetay);

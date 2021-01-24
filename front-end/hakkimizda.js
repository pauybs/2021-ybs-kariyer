import React, { Component, Fragment } from "react";
import {
  CardText,
  Row,
  Card,
  CardTitle,
  CardImg,
  CardImgOverlay
} from "reactstrap";
import { Colxx, Separator } from "../../components/common/CustomBootstrap";
import Breadcrumb from "../../containers/navs/Breadcrumb";
const ImageOverlayCard = ({bigTitle, link, badgeTitle, smallTitle, image}) => {
  return (
        
          <Colxx xxs="12" xs="12" lg="12">
            
            <Card inverse className="mb-4" style={{height:100}} >
              <CardImg
                src={image}
                
                alt="Card image cap"
              />
              
              <CardImgOverlay>
             
              <span className="badge badge-pill badge-theme-3 align-self-start mb-1">
                {badgeTitle}
              </span>
                <CardTitle>{bigTitle}</CardTitle>
              
               
              </CardImgOverlay>
            </Card>
            
          </Colxx>
         
  );
};
export default class BlankPage extends Component {
    render() {
        return (
            <Fragment>
            <Row>
              <Colxx xxs="12">
                <Breadcrumb heading="Hakkımızda" match={this.props.match} />
                <Separator className="mb-5" />
              </Colxx>
            </Row>
            <Row>
              {anasayfaBlogData && anasayfaBlogData.length > 0 && anasayfaBlogData.map(item => {
                return (
                  
                    <ImageOverlayCard {...item}>
                      </ImageOverlayCard>
                   
                    
                 
                );
              })}
              <Card  className="mb-4">
              <CardText className="p-2" dangerouslySetInnerHTML={{ __html:hakkimizda }}>
                  
                  </CardText>
              </Card>
              
            </Row>
          </Fragment>
        )
    }
}

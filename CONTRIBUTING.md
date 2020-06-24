
## Contributing to MTOM Server Soap

Contributions to Ignis Inventum Patterns are welcome and appreciated. Please read this document to understand the process for contributing.

## # MTOM Server Soap
Some Web service APIs take as parameters files that may contain binary data.

The MTOM W3C recommendation defines how binary data can be transmitted as a binary attachment. This way it avoids the size overhead and the loss in speed of the data transmission.

SwA is a W3C Note. It was submitted as a proposal, but it was not adopted by the W3C. Instead, MTOM is the W3C Recommendation for handling binary data in SOAP messages. With the release of SOAP 1.2 additionally the note SOAP 1.2 Attachment Feature was published.

This package extends the PHP SOAP server class to be able to decode binary data attachments sent in a SOAP request using MTOM (Message Transmission Optimization Mechanism) or SwA (Soap With Attachments) and can detect binary files encoded more efficiently for transmission and decode the attached binary data for regular SOAP server handling.

This package is somewhat inspired in the BeSimple SOAP server package.

## Contribution process

## Different ways to contribute

* You can [report an issue](https://github.com/cleidsondias/mtom-server-soap/issues) you found
* Contribute [to our wiki](https://github.com/cleidsondias/mtom-server-soap/wiki) 
* Create a another dessing patterns 
* Contribute code to Ignis Inventum Patterns! 

All repositories are hosted on GitHub. Pick up any pending feature or bug, big or small, then send us a pull request. Even fixing broken links is a big, big help!

## How do I start contributing

### Do you like to code?

- clone the branch develop 
- git checkout -b my-new-feature
- create a new patterns or fix
- Submit a pull request

### Do you like organizing?
- Link to duplicate issues, and suggest new issue labels, to keep things organized
- Go through open issues and suggest closing old ones.
- Ask clarifying questions on recently opened issues to move the discussion forward

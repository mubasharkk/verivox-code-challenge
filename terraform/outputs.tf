output "websiteurl" {
    value = "http://${aws_route53_record.challengers.name}"
}

output "dns-name" {
    value = "http://${aws_instance.web.public_dns}"
}

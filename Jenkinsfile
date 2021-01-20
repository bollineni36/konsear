node{
  def Namespace = "default"
  def repository = "167250654448.dkr.ecr.us-east-1.amazonaws.com/dockertest"
//  def ImageName = "jenkinsfiledockerimage"
  def imageTag = "v4"
  
  stage('Checkout'){
      checkout([$class: 'GitSCM', branches: [[name: '*/master']], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: 'bollinenni36', url: 'https://github.com/bollineni36/konsear.git']]])
      
  }
  stage('Docker Build, Push'){
      sh "docker build -t $repository:${imageTag} ."
      sh "whoami"
 //     sh "aws ecr get-login-password --region us-east-1 | docker login --username AWS --password-stdin 167250654448.dkr.ecr.us-east-1.amazonaws.com"
      sh "docker login $repository"
      sh label: '', script: 'sleep 10'
      sh "docker push $repository:${imageTag}"
      
      
    }
    stage('Creating the Helm charts'){
        sh "helm create konsearapp"
        sh label: '', script: 'sleep 10'
        sh "sed -i 's,nginx,'acrncpltest.azurecr.io/jenkins/php',g' < ./konsearapp/values.yaml ./konsearapp/values.yaml"
        sh "sed -i 's,stable,'${imageTag}',g' < ./konsearapp/values.yaml ./konsearapp/values.yaml"
        sh "sed -i 's,ClusterIP,'LoadBalancer',g' < ./konsearapp/values.yaml ./konsearapp/values.yaml"
        
    }
    stage('Deploying the helm charts'){
        
        sh "helm install konsearapp --name konsearhelm1"
    }
}



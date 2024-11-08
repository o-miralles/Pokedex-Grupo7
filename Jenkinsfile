pipeline {
    agent any

    environment {
        // Nombre del servidor SonarQube configurado en Jenkins
        SONARQUBE_SERVER = 'SonarQube_Local'
        // Agregar sonar-scanner al PATH
        PATH = "/opt/sonar-scanner/bin:${env.PATH}"
    }

    stages {
         stage('Deploy to Web Server') {
            steps {
                // Usar credenciales SSH para conectarse al servidor web
                sshagent(['webserver_ssh_credentials_id']) {
                    sh '''
                        # Asegurarse de que el servidor SSH es confiable y no pregunte por la clave
                        ssh-keyscan -H 10.30.212.72 >> ~/.ssh/known_hosts
                        ssh grupo7@10.30.212.72 'cd /var/www/html && git clone https://github.com/o-miralles/Pokedex-Grupo7.git || cd /var/www/html/Pokedex-Grupo7 && git pull'
                    '''
                }
            }
        }
        stage('ZAP Analysis') {
            steps {
                script {
                    // Ejecutar ZAP dentro de un contenedor Docker sin usar zap-cli
                    docker.image('owasp/zap2docker-stable').inside('--network host') {
                        sh '''
                            # Iniciar ZAP en modo demonio
                            zap.sh -daemon -host 127.0.0.1 -port 8090 -config api.disablekey=true &
                            # Esperar a que ZAP esté listo
                            timeout=120
                            while ! curl -s http://127.0.0.1:8090; do
                                sleep 5
                                timeout=$((timeout - 5))
                                if [ $timeout -le 0 ]; then
                                    echo "ZAP no se inició a tiempo"
                                    exit 1
                                fi
                            done
                            # Ejecutar el escaneo completo con zap-full-scan.py
                            zap-full-scan.py -t http://10.30.212.71 -r zap_report.html -I
                            # Apagar ZAP
                            zap.sh -cmd -shutdown
                        '''
                    }
                }
                // Publicar el reporte de ZAP
                publishHTML(target: [
                    reportDir: "${env.WORKSPACE}",
                    reportFiles: 'zap_report.html',
                    reportName: 'Reporte ZAP'
                ])
            }
        }
    }
}
